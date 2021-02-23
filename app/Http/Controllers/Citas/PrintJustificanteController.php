<?php

namespace App\Http\Controllers\Citas;

use PDF;
use Carbon\Carbon;
use App\Models\Cita;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PrintJustificanteController extends Controller
{
    protected $cita;

    public function show($id){

        return Cita::with(['paciente'])->findOrFail($id);

    }

    public function print(Request $request){

        $data = $request->validate([
            'file'      => ['required','boolean'],
            'cita_id'   => ['required','integer'],
            'anexo'     => ['nullable'],
        ]);

        $this->cita = Cita::with(['paciente','tratamiento'])->findOrFail($data['cita_id']);

        // if ($this->cita->tipo_id == 4 && !esGestor()){
        //     return abort(404, 'No puedes visualizar este albarán - Gestor Requerido');
        // }

        // constrola si la compra está recuperada para poder imprimir como factura
        // if ($this->cita->fase_id != 5 || $this->cita->factura <= 0 || $this->cita->factura==''){
        //     return redirect()->route('albaran.print', ['id' => $id]);
        // }
            //return abort(411,'La compra no está recuperada y/o facturada');;

        $empresa  = session()->get('empresa');

        ob_end_clean();

        $this->setPrepararFormulario($empresa);

        $this->frmJustificante($data['anexo']);

        PDF::Close();

        if ($data['file']){
            if (file_exists(storage_path('pdfdocs'))==false)
                mkdir(storage_path('pdfdocs'), '0755');

            if ($this->cita->factura > 0)
                PDF::Output(storage_path('pdfdocs/'.$this->cita->ser_fac.'.pdf'), 'F');
            else
                PDF::Output(storage_path('pdfdocs/'.$this->cita->alb_ser.'.pdf'), 'F');

        }
        else{
            return PDF::Output('JUS'.$this->cita->id.'.pdf','I');
        }

        PDF::reset();
    }

     /**
     *
     * Formulario de factura de recuperación de compras.
     *
     */
    private function frmJustificante($anexo)    {


        PDF::AddPage();

        $this->printBody($anexo);

       // $this->impNotas();

    }


    /**
     *
     * @param Model Albalins
     *
     */
    private function printBody($anexo) {


        PDF::Ln();

        $f = Carbon::parse($this->cita->fecha);
        $txt = "En ".session('empresa')->poblacion.', a '.$f->isoFormat('D [de] MMMM [de] YYYY[.]');

        PDF::Write($h=0, $txt, '', 0, 'L', true, 0, false, true, 0);

        PDF::Ln();
        PDF::Ln();
        PDF::Ln();

        $txt = "   "."Solicitado por D./Dña.: %n que según consta en nuestra base de datos tenía una cita en este Centro a las %h horas del día de la fecha para recibir tratamiento fisioterápico.\n";

        $txt = str_replace('%n', $this->cita->paciente->nomape, $txt);
        $txt = str_replace('%h', substr($this->cita->hora,0,5), $txt);

        PDF::Write($h=0, $txt, '', 0, 'J', true, 0, false, true, 0);

        PDF::Ln();

        if ($anexo != ""){
            $anexo = "   ".$anexo."\n";
            PDF::Write($h=0, $anexo, '', 0, 'J', true, 0, false, true, 0);
        }

        // $html='<textarea cols="78" rows="3" name="text"></textarea><br/>';
        // //PDF::writeHTML($html, false, 0, false, 0, 'J');
        // PDF::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, 'J', true);


        PDF::Ln();
        PDF::Ln();
        PDF::Ln();


        $txt = "Firma y Sello del Centro";
        PDF::Write($h=0, $txt, '', 0, 'C', true, 0, false, true, 0);




    }

         /**
     *
     * @param Model Empresa
     *
     */
    private function setPrepararFormulario($empresa){

        PDF::setHeaderCallback(function($pdf) {

            //if (session('empresa')->img_logo > ""){
            if (true){
                $pdf->setImageScale(1.60);

                // $pdf->SetXY(14, 5);
                // $pdf->Image($imagen, '', '', 42, 15, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
                //Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)

                $f = str_replace('storage', 'public', '/storage/logos/logo.jpg');

                if (Storage::exists($f)){
                    $file = '@'.(Storage::get($f));
                    $pdf->setJPEGQuality(75);
                    $pdf->Image($file, $x='5', $y='4', $w=0, $h=25, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false);
                }

            }

            $pdf->SetFont('helvetica', 'R', 10, '', false);
            $y = 10;
            $pdf->SetXY(4, 10);
            $pdf->Write($h=0, 'Tf.: '.getTelefonoFijo(session('empresa')->telefono1), '', 0, 'R', true, 0, false, true, 0);
            $pdf->SetXY(4, 16);
            $pdf->Write($h=0, 'WhatsApp: '.getTelefonoMovil(session('empresa')->telefono2), '', 0, 'R', true, 0, false, true, 0);
            $pdf->SetXY(4, 22);
            $pdf->Write($h=0, session('empresa')->email, '', 0, 'R', true, 0, false, true, 0);

            $pdf->SetFont('helvetica', 'B', 16, '', false);

            $txt = "JUSTIFICANTE DE PRESENCIA";
            $pdf->SetXY(4, 36);
            $pdf->Write($h=0, $txt, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

            $pdf->SetFont('helvetica', 'R', 9, '', false);

        });

        PDF::setFooterCallback(function($pdf) {


            // $f = str_replace('storage', 'public', '/storage/logos/sello.png');

            // $file = '@'.(Storage::get($f));
            // $pdf->setJPEGQuality(75);
            // $pdf->Image($file, $x='140', $y='220', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false);

            PDF::SetFont('helvetica', 'R', 6);

            $html='En cumplimiento al Reglamento (UE) 2016/679 del Parlamento Europeo y del Consejo, de 27 de abril de '.
                    '2016, relativo a la protección de las personas físicas en lo que respecta al tratamiento de datos personales y a la libre '.
                    'circulación de estos datos SE INFORMA: Los datos de carácter personal solicitados y facilitados por usted, son incorporados a un fichero de '.
                    'titularidad privada cuyo responsable y único destinatario es %e. Solo serán solicitados aquellos datos estrictamente necesarios '.
                    'para prestar adecuadamente el servicio.'.
                    'Todos los datos recogidos cuentan con el compromiso de confidencialidad exigido por la normativa, '.
                    'con las medidas de seguridad establecidas legalmente, y bajo ningún concepto son cedidos o tratados '.
                    'por terceras personas, físicas o jurídicas, sin el previo consentimiento del cliente. '.
                    'Puede ejercitar los derechos de acceso, rectificación, cancelación, oposición, limitación y '.
                    'portabilidad indicándolo por escrito a %e, '.session()->get('empresa')->direccion.' '.session()->get('empresa')->cpostal.' '.
                    session()->get('empresa')->poblacion.".\n";

            $html = str_replace('%e', session()->get('empresa')->razon, $html);


            //$this->Write($h=0, $html, $link='', $fill=0, $align='J', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
            PDF::Write($h=0, $html, '', 0, 'J', true, 0, false, true, 0);

            PDF::Ln();
            PDF::SetFont('helvetica', 'R', 7);

            $pdf->Write($h=0, session()->get('empresa')->txtpie1, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
            $pdf->Write($h=0, session()->get('empresa')->txtpie2, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);



        });


                // set document information
        PDF::SetCreator(session('username'));
        PDF::SetAuthor($empresa->nombre);
        PDF::SetTitle('Justificante');
        PDF::SetSubject('');

        // set default header data
        PDF::SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        // set header and footer fonts
        PDF::setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        PDF::setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        PDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        //PDF::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        PDF::SetMargins(13, 58, PDF_MARGIN_RIGHT);
        PDF::SetHeaderMargin(PDF_MARGIN_HEADER);
        //PDF::SetFooterMargin(PDF_MARGIN_FOOTER);
        PDF::SetFooterMargin(34);

        // set auto page breaks
        PDF::SetAutoPageBreak(TRUE, 34);

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
