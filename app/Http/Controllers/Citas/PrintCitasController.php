<?php

namespace App\Http\Controllers\Citas;

use PDF;
use Carbon\Carbon;
use App\Models\Cita;
use App\Models\Adjunto;
use App\Models\Pacbono;
use App\Models\Historia;
use App\Models\Paciente;
use App\Models\Facultativo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PrintCitasController extends Controller
{

    public function index(){


        $fisios = Facultativo::selFacultativos();

        if (request()->wantsJson())
            return [
                'facultativos' => $fisios,
                'facultativo_id' => session('facultativo_id')
            ];

    }

    public function recordar($paciente_id){

        $paciente = Paciente::findOrFail($paciente_id);

        $empresa  = session('empresa')->nombre;
        $telefono  = session('empresa')->telefono1;

        $logo = '/storage/logos/logotk.jpg';

        $citas = Cita::where('paciente_id', $paciente_id)
                    ->where('fecha', '>=', Carbon::today())
                    ->where('estado_id', 1)
                    ->orderBy('fecha','asc')
                    ->orderBy('hora', 'asc')
                    ->get()
                    ->take(10);

        return view('citas', compact('citas','paciente', 'empresa', 'telefono','logo'));


    }

    public function listas(Request $request){

        $data = $request->validate([
            'area_id'       => ['required', 'integer'],
            'fecha'         => ['required', 'date'],
            'facultativo_id'=> ['nullable', 'integer'],
            'turno'         => ['required', 'max:1'],
            ]);

        $citas = Cita::with(['tratamiento','facultativo','paciente'])
                        ->where('area_id', $data['area_id'])
                        ->whereDate('fecha', $data['fecha'])
                        ->facultativo($data['facultativo_id'])
                        ->orderBy('facultativo_id')
                        ->orderBy('hora')
                        ->get();


        ob_end_clean();

        $empresa  = session('empresa');
        $this->setPrepararFormulario($empresa);

        $this->frmLista($citas, $data['turno']);

        PDF::Close();

        // if ($data['file']){
        //     if (file_exists(storage_path('pdfdocs'))==false)
        //         mkdir(storage_path('pdfdocs'), '0755');

        //     if ($this->cita->factura > 0)
        //         PDF::Output(storage_path('pdfdocs/'.$this->cita->ser_fac.'.pdf'), 'F');
        //     else
        //         PDF::Output(storage_path('pdfdocs/'.$this->cita->alb_ser.'.pdf'), 'F');

        // }
        // else{
        //     return PDF::Output('Listas.pdf','I');
        // }

        return PDF::Output('Listas.pdf','I');

        PDF::reset();


    }

    /**
     *
     * Formulario de factura de recuperación de compras.
     *
     */
    private function frmLista($citas, $turno){

        PDF::AddPage();

        $maxh=5;
        PDF::SetFont('helvetica', 'R', 7, '', false);
        PDF::SetTextColor(0,0,0);

        //PDF::SetFillColor(152, 255, 28);
        PDF::SetFillColor(180,255,110);

        $fis_ant = null;

        foreach ($citas as $cita){

            if ($fis_ant != $cita->facultativo_id){
                $fis_ant = $cita->facultativo_id;
                PDF::SetTextColor(24,0,200);
                //PDF::SetTextColor(205,255,134);
                PDF::SetFont('helvetica', 'B', 9, '', false);
                PDF::MultiCell($w=198, 7, trim($cita->facultativo->nombre), $border='TB', $align='C', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, 7,'M');
                PDF::SetFont('helvetica', 'R', 7, '', false);
                PDF::SetTextColor(0,0,0);
            }

            switch ($turno) {
                case 'M':
                    if ($cita->hora >= "15:00:00")
                        continue 2;
                    break;

                case 'T':
                    if ($cita->hora < "15:00:00")
                        continue 2;
                    break;
                default:
                    break;
            }


            $next_cita = Cita::where('area_id', $cita->area_id)
                                ->where('fecha', '>', $cita->fecha)
                                ->where('paciente_id', $cita->paciente_id)
                                ->where('estado_id', '<>', '4')
                                ->orderBy('fecha')
                                ->first();

            $informes_no_leidos = Adjunto::where('paciente_id', $cita->paciente_id)
                                        ->where('leido', false)
                                        ->get()
                                        ->count();

            $informes_no_leidos = $informes_no_leidos > 0 ? '('.$informes_no_leidos.' Informes no leídos)' : '';



            $ultimas = $this->ultimas($cita);


            $antecedentes = trim($cita->paciente->ant1.' '.
                $cita->paciente->ant2.' '.
                $cita->paciente->ant3.' '.
                $cita->paciente->ant4.' '.
                $cita->paciente->ant5.' '.
                $cita->paciente->ant6.' '.
                $cita->paciente->antobs);

            $embarazada = $cita->paciente->embarazada ? 'Embarazada!' : '';
            $antecedentes = $antecedentes;



            $maxh = 4;
            $next='';
            if ($next_cita != ''){
                $f = Carbon::parse($next_cita->fecha)->isoFormat('ddd D/MM');
                $next = 'Cita: '.$f;
            }

            $bono = Pacbono::getSesionesBono($cita->paciente_id, $cita->bono);
            if ($cita->paciente_id == 7510){
                $hc = Historia::where('paciente_id', $cita->paciente_id)->orderBy('fecha','desc')->first();
            }
            $hc = Historia::where('paciente_id', $cita->paciente_id)->orderBy('fecha','desc')->first();


            $notas = $cita->notas." ".$cita->paciente->notas2;

            PDF::MultiCell($w=10, $maxh, $cita->hora, $border='T', $align='L', $fill=0, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh,'M');
            if ($cita->tratamiento->nombre_reducido == 'MTO'){
                PDF::MultiCell($w=8, $maxh, $cita->tratamiento->nombre_reducido, $border='T', $align='L', $fill=1, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh,'M');
                PDF::MultiCell($w=40, $maxh, trim($cita->paciente->ape), $border='T', $align='L', $fill=1, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh,'M');
            }
            else{
                PDF::MultiCell($w=8, $maxh, $cita->tratamiento->nombre_reducido, $border='T', $align='L', $fill=0, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh,'M');
                PDF::MultiCell($w=40, $maxh, $cita->paciente->ape, $border='T', $align='L', $fill=0, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh,'M');
            }

            PDF::MultiCell($w=34, $maxh, $next, $border='T', $align='L', $fill=0, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh,'M');
            $bono_txt = '';
            if ($bono['numero_bono'] > 0){
                $bono_txt = 'Bono: +'.$bono['resto'];//.'/'.$bono['sesiones'];
            }
            PDF::MultiCell($w=12, $maxh, $bono_txt, $border='T', $align='L', $fill=0, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh,'M');

            PDF::SetTextColor(240,0,0);
            PDF::MultiCell($w=18, $maxh, $embarazada, $border='T', $align='L', $fill=0, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh,'M');
            PDF::MultiCell($w=60, $maxh, $informes_no_leidos, $border='T', $align='L', $fill=0, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh,'M');
            PDF::SetTextColor(0,0,0);

            //PDF::writeHTML($ultimas, true, false, true, false, '');
            PDF::MultiCell($w=28, $maxh, $ultimas, $border='T', $align='L', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh,'M');


            if (strlen($antecedentes) > 40){
                $maxh = PDF::getStringHeight(196, $antecedentes);
                if ($maxh < 4) $maxh = 4;
                $max_max = $maxh + 0.5;
            }else{
                $maxh = 4;
                $max_max = $maxh;
            }

            if ($antecedentes != ''){
                PDF::MultiCell($w=6, $max_max, '', $border='', $align='L', $fill=0, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $max_max,'M');
                PDF::MultiCell($w=194, $max_max, trim($antecedentes), $border='', $align='L', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $max_max,'M');
            }

            $patologia='NO EXISTE HISTORIA!';
            if ($hc != ''){

                strlen($hc->motivo) > 0 ? $motivo = "<b>MC:</b>".$hc->motivo : $motivo = null;
                (strlen($hc->exploracion) > 0) ? $explora = "<b>EX:</b>".$hc->exploracion : $explora = null;

                //$patologia=getFecha($hc->updated_at)." ".$motivo." ".$explora." <b>JC:</b> ".$hc->juicio.' <b>TR:</b> '.$hc->tratamiento.' '.'('.$hc->username.')';
                $patologia=getFecha($hc->updated_at)." <b>JC:</b> ".$hc->juicio.' <b>TR:</b> '.$hc->tratamiento.' '.'('.$hc->username.')';

            }

            if (strlen($patologia) > 40){
                $maxh = PDF::getStringHeight(196, $patologia);
                if ($maxh < 4) $maxh = 4;
                $max_max = $maxh + 1;
            }else{
                $maxh = 4;
                $max_max = $maxh;
            }

            $max_max += 0.2;

            PDF::MultiCell($w=6, $max_max, '', $border='', $align='L', $fill=0, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $max_max,'M');
            PDF::MultiCell($w=194, $max_max, trim($patologia), $border='', $align='L', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $max_max,'M');

            if (trim($notas) != ''){
                PDF::MultiCell($w=6, 4, '', $border='', $align='L', $fill=0, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, 4,'M');
                PDF::MultiCell($w=194, 4, trim($notas), $border='', $align='L', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, 4,'M');
            }




            // PDF::MultiCell($w=40, $maxh, 'Tratamiento', $border='', $align='C', $fill=0, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh,'M');
            // PDF::MultiCell($w=30, $maxh, 'Ult. Cita', $border='', $align='C', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh,'M');

        }

    }

    private function ultimas($cita){

        $ultimas = Cita::where('area_id', $cita->area_id)
                        ->where('fecha', '<', $cita->fecha)
                        ->where('paciente_id', $cita->paciente_id)
                        ->orderBy('fecha','desc')
                        ->get()
                        ->take(5);


        if ($ultimas == '') return '';

        $str='';
        $today = Carbon::today();
        $i=0;
        foreach ($ultimas as $row){
            if ($i >= 3) break;

            $dt = Carbon::parse($row->fecha);

            if ($row->estado_id == 4)
                $str.='<span style="color: red;">(A) </span>';
            else{
                $dif = $dt->diffInDays($today);
                if ($dif == 0) continue;
                $str.='<span style="color: blue;">('.$dif.'D) </span>';
            }

            $today = $dt;

            $i++;

        }

        return $str;

    }

         /**
     *
     * @param Model Empresa
     *
     */
    private function setPrepararFormulario($empresa){

        PDF::setHeaderCallback(function($pdf) {

            // $maxh=6;

            // $pdf->SetFont('helvetica', 'B', 9, '', false);
            // $y = 10;
            // $pdf->SetXY(4, 10);

            // //$pdf->SetFillColor(220, 255, 220);
            // $pdf->SetTextColor(24,0,200);

            // $pdf->MultiCell($w=18, $maxh, 'Hora', $border='LRTB', $align='C', $fill=0, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh,'M');
            // $pdf->MultiCell($w=40, $maxh, 'Paciente', $border='LRTB', $align='C', $fill=0, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh,'M');
            // $pdf->MultiCell($w=114, $maxh, 'Notas', $border='LRTB', $align='C', $fill=0, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh,'M');
            // $pdf->MultiCell($w=30, $maxh, 'Ult. Cita', $border='LRTB', $align='C', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh,'M');


        });

        PDF::setFooterCallback(function($pdf) {

        });


                // set document information
        PDF::SetCreator(session('username'));
        PDF::SetAuthor($empresa->nombre);
        PDF::SetTitle('Listas');
        PDF::SetSubject('');

        // set default header data
        PDF::SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        // set header and footer fonts
        PDF::setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        PDF::setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        PDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        PDF::SetMargins(4, 0, PDF_MARGIN_RIGHT);
        //PDF::SetMargins(13, 58, PDF_MARGIN_RIGHT);
        PDF::SetHeaderMargin(PDF_MARGIN_HEADER);
        PDF::SetFooterMargin(PDF_MARGIN_FOOTER);
        //PDF::SetFooterMargin(34);

        // set auto page breaks
        PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_FOOTER);

        // set image scale factor
        PDF::setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            PDF::setLanguageArray($l);
        }

        // ---------------------------------------------------------

    }

}
