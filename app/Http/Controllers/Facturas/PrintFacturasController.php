<?php

namespace App\Http\Controllers\Facturas;

use PDF;
use Carbon\Carbon;
use App\Models\Fpago;
use App\Models\Cuenta;
use App\Models\Factlin;
use App\Models\Factura;
use App\Jobs\SendFactura;
use App\Mail\FacturaMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PrintFacturasController extends Controller
{
    public function mail(Request $request, Factura $factura){

        //$albaran = Factura::with(['cliente'])->findOrFail($factura->id);
        $factura->load(['paciente']);

        if (!esAdmin()){
            return abort(404, 'No puedes visualizar este albarán - Gestor Requerido');
        }

        if ($factura->paciente->email=='')
            return response('El paciente no tiene email configurado', 403);
        // elseif (session('empresa')->email=='')
        //     return response('Configurar email empresa', 403);

        $from = config('mail.from.address');
        //$from = str_replace('info','noreply', $from);

        //\Log::info($from);

        $this->print($factura->id, true);

        $data = [
            'nom_ape'=> session('empresa')->nom_ape,
            'from'=> $from,
            'msg' => null,
            'factura' => $factura
        ];

        // con esto previsualizamos el mail
        //return new FacturaMail($data);

        dispatch(new SendFactura($data));

        // if ($factura->factura > 0){
        //     $data_alb['fecha_notificacion'] =  Carbon::now();
        //     $data_alb['username']   = session('username');;

        //     $factura->update($data_alb);
        // }

        if (request()->wantsJson())
            return [
                //'albaran' => $factura->load(['cliente','tipo','fase','fpago','cuenta','procedencia','motivo']),
                'message' => 'Mail enviado'];

    }

    public function print($id, $file=false){

        $this->factura = Factura::with(['paciente'])->findOrFail($id);

        if (!esAdmin()){
            return abort(404, 'No puedes visualizar este albarán - Gestor Requerido');
        }

        $this->factlin = Factlin::where('factura_id', $this->factura->id)
                                ->orderBy('id')
                                ->get();

        $empresa  = session()->get('empresa');

        ob_end_clean();

        $this->setPrepararFormulario($empresa);

        $this->frmAlbaran();

        PDF::Close();

        if ($file){
            if (file_exists(storage_path('facturas'))==false)
                mkdir(storage_path('facturas'), '0755');

            PDF::Output(storage_path('facturas/'.$this->factura->ser_fac.'.pdf'), 'F');

        }
        else{
            PDF::Output('F'.$this->factura->factura.'.pdf','I');
        }

        PDF::reset();
    }

     /**
     *
     * Formulario de factura de recuperación de compras.
     *
     */
    private function frmAlbaran()    {


        PDF::AddPage();

        // cabecera cliente
        //$this->setCabeceraAlbaran();


        $this->printAlbalin($this->factlin);

        $this->formaDePago();

        if ($this->factura->notas > "")
            $this->impNotas();


    }

     /**
     *
     * @param Model Cliente
     *
     */
    private function setCabeceraAlbaran(){

        $fecha =  getFecha($this->factura->fecha);
        $num_doc = $this->factura->ser_fac;

        //PDF::SetFillColor(235, 235, 235);
        PDF::SetFillColor(215, 235, 255);

        PDF::SetFont('helvetica', 'R',11, '', false);
        PDF::setXY(122,14);
        PDF::MultiCell(40, 8, $fecha,'', 'C', 1, 1, '', '', true,0,false,true,8,'M',false);

        PDF::setXY(165,14);
        PDF::MultiCell(36, 8,  $num_doc,'', 'C', 1, 1, '', '', true,0,false,true,8,'M',false);


        PDF::SetFont('helvetica', '', 9, '', false);
        PDF::setXY(115,28);
        PDF::MultiCell(90, 5,  'NIF.: '.$this->factura->cif,'', 'L', 0, 1, '', '', true,0,false,true,5,'M',false);

        $nom_ape = $this->factura->razon;

        PDF::setXY(115,35);
        PDF::MultiCell(90, 5,  $nom_ape, '', 'L', 0, 1, '', '', true,0,false,true,5,'M',false);
        PDF::setX(115);
        PDF::MultiCell(90, 5,  $this->factura->paciente->direccion,'', 'L', 0, 1, '', '', true,0,false,true,5,'M',false);
        PDF::setX(115);
        PDF::MultiCell(90, 5,  $this->factura->paciente->cpostal.' '.$this->factura->paciente->poblacion,'', 'L', 0, 1, '', '', true,0,false,true,5,'M',false);
        if ($this->factura->paciente->poblacion != $this->factura->paciente->provincia){
            PDF::setX(115);
            PDF::MultiCell(90, 5,  $this->factura->paciente->provincia,'', 'L', 0, 1, '', '', true,0,false,true,5,'M',false);
        }

    }

    /**
     *
     * @param Model Albalins
     *
     */
    private function printAlbalin($lineas) {

        $this->cabeLin();

        $this->totales = Factlin::totalFactura($this->factura->id);

        foreach ($lineas as $row) {


            $h = $alto=PDF::getStringHeight(95,$row->concepto);
            $h = $h + 2;

            $y = round(PDF::getY());

            // $txt .= " **PW: ".$y." H: ".$h;
            if ($y+$h >= 240){
                PDF::AddPage();
                //$this->setCabeceraAlbaran();
                $y = round(PDF::getY());
                $this->cabeLin();
            }


            PDF::MultiCell($w=140, $h, $row->concepto, $border='R', $align='L', $fill=0, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);
            PDF::MultiCell($w=14, $h, getDecimal($row->iva, 2), $border='R', $align='R', $fill=0, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);
            PDF::MultiCell($w=26, $h, getDecimal($row->importe), $border='', $align='R', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);

        }

        $h=2;

        PDF::MultiCell($w=140, $h, '', $border='R', $align='L', $fill=0, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);
        PDF::MultiCell($w=14, $h, '', $border='R', $align='L', $fill=0, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);
        PDF::MultiCell($w=26, $h, '', $border='', $align='R', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);
        $h=6;
        PDF::MultiCell($w=140, $h, '', $border='T', $align='L', $fill=0, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);
        PDF::MultiCell($w=14, $h, '', $border='T', $align='L', $fill=0, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);
        PDF::MultiCell($w=26, $h, '', $border='T', $align='R', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);

        PDF::SetFont('helvetica', 'R', 10, '', false);

        PDF::SetFillColor(215, 235, 255);
        PDF::MultiCell(112, 8, '', '', '', 0, 0, '', '', true);


        PDF::MultiCell(40, 8, 'TOTAL', 0, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(3, 8, '', 0, 'R', 0, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(26, 8, getCurrency($this->totales), 0, 'R', 1, 1, '', '', true, 0, false, true, 8, 'M');


        $leyenda = "IVA 0,00%: Actividad exenta de IVA s/Art. 20 IVA";
        PDF::Ln();
        PDF::MultiCell(110, 8, $leyenda, '', '', 0, 1, '', '', true);


        return;

        PDF::SetFont('helvetica', 'R', 8, '', false);
        if ($this->factura->tipo_id == 3){

            $iva = Iva::findOrFail(2);

            PDF::MultiCell(110, 8, $iva->leyenda, '', '', 0, 1, '', '', true);
        }
        //else{

        if ($this->factura->factura > 0){
            $linea = 0;
            foreach ($this->totales['desglose_iva'] as $tipos_iva){
                if ($tipos_iva['rebu'] == true)
                    continue;

                $linea++;
                if ($linea == 1){
                    PDF::MultiCell(24, 6,'Base IVA', 'RB', 'C', 0, 0, '', '', true);
                    PDF::MultiCell(16, 6, '% IVA', 'RB', 'C', 0, 0, '', '', true);
                    PDF::MultiCell(24, 6, 'Cuota', 'B', 'C', 0, 1, '', '', true);
                }
                PDF::MultiCell(24, 6, getDecimal($tipos_iva['base_iva']), 'R', 'R', 0, 0, '', '', true);
                PDF::MultiCell(16, 6, getDecimal($tipos_iva['por_iva']), 'R', 'R', 0, 0, '', '', true);
                PDF::MultiCell(24, 6, getDecimal($tipos_iva['cuota_iva']), '', 'R', 0, 1, '', '', true);


            }

            PDF::Ln();

            foreach ($this->totales['desglose_iva'] as $tipos_iva){
                if ($tipos_iva['cuota_iva'] == 0){
                    $iva = Iva::findOrFail($tipos_iva['id']);
                    PDF::MultiCell(140, 6, '* ('.$iva->id.') '.$iva->leyenda, '', 'L', 0, 1, '', '', true);
                }
            }

            PDF::Ln();
        }


    }

    private function cabeLin(){


        PDF::Ln(5);
        PDF::SetFont('helvetica', 'RB', 8, '', false);

        PDF::Cell(140, 6, 'CONCEPTO', 'TRB', 0, 'C');
        PDF::Cell(14, 6, 'IVA', 'TRB', 0, 'C');
        PDF::Cell(26, 6, 'IMPORTE', 'TB', 0, 'C');
        // PDF::MultiCell(160, 4,$txt, 'TRB', 'C', 0, 0, '', '', true);
        // PDF::MultiCell(20, 4,$imp, 'TB', 'C', 0, 1, '', '', true);
        PDF::Ln();

        PDF::SetFont('helvetica', 'R', 8, '', false);
        PDF::MultiCell(140, 2,"", 'R', '', 0, 0, '', '', true);
        PDF::MultiCell(14, 2,"", 'R', '', 0, 0, '', '', true);
        PDF::MultiCell(26, 2,"", '', '', 0, 1, '', '', true);
    }

    private function formaDePago(){

        PDF::SetFillColor(215, 235, 255);

        if ($this->factura->factura == "")
            return;

        $txt_fpago = "FORMA DE PAGO: ";
        $fpago = Fpago::findOrfail($this->factura->fpago_id);
        if ($fpago->id == 4 && !is_null($this->factura->cuenta_id)){
            $cuenta = Cuenta::findOrfail($this->factura->cuenta_id);
            $txt_fpago .= strtoupper($fpago->nombre).' IBAN '.getIbanPrint($cuenta->iban);
        }else{
            $txt_fpago .= strtoupper($fpago->nombre);
        }

        PDF::Ln();

        PDF::SetFont('helvetica', 'R', 9, '', false);
        PDF::MultiCell(184, 8, $txt_fpago, 'TB', 'L', 0, 1, '', '', true, 0, false, true, 8, 'M');

    }

    private function impNotas(){

        PDF::Ln();

        PDF::SetFillColor(215, 235, 255);

        PDF::SetFont('helvetica', 'I', 8, '', false);
        PDF::MultiCell(184, 6, 'Observaciones', '', 'L', 1, 1, '', '', true, 0, false, true, 6, 'M');
        PDF::SetFont('helvetica', 'R', 9, '', false);
        PDF::MultiCell(184, 16, $this->factura->notas, '', 'L', 1, 1, '', '', true, 0, false, true, 16, 'M');

    }

         /**
     *
     * @param Model Empresa
     *
     */
    private function setPrepararFormulario($empresa){

        PDF::setHeaderCallback(function($pdf) {

            //if (session('empresa')->img_logo > ""){
                $pdf->setImageScale(1.60);

                //$f = str_replace('storage', 'public', session()->get('empresa')->img_logo);
                $f = str_replace('storage', 'public', '/storage/logos/logo.jpg');

                if (Storage::exists($f)){
                    $file = '@'.(Storage::get($f));
                    $pdf->setJPEGQuality(75);
                    $pdf->Image($file, $x='5', $y='4', $w=0, $h=25, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false);
                }

            //}

            $pdf->SetFont('helvetica', 'B', 16, '', false);


            $txt = "FACTURA";

            $pdf->SetXY(4, 5);
            $pdf->Write($h=0, $txt, $link='', $fill=0, $align='R', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

            $pdf->SetFont('helvetica', 'R', 9, '', false);

            $y = 30;
            $pdf->SetXY(10, $y);
            $pdf->Write($h=0,  session('empresa')->nom_ape, '', 0, 'L', true, 0, false, true, 0);
            $pdf->SetXY(10, $y+=5);
            $pdf->Write($h=0,  session('empresa')->direccion, '', 0, 'L', true, 0, false, true, 0);
            $pdf->SetXY(10, $y+=5);
            $pdf->Write($h=0,  session('empresa')->cpostal.' '.session('empresa')->poblacion, '', 0, 'L', true, 0, false, true, 0);
            $pdf->SetXY(10, $y+=5);
            $pdf->Write($h=0,  'CIF.: '.session('empresa')->cif, '', 0, 'L', true, 0, false, true, 0);

            $this->setCabeceraAlbaran();

            //$pdf->MultiCell(34, 157, session('empresa')->nom_ape, 0, 'L', 0, 0, '', '', true);
            //$pdf->MultiCell(70, 15, session('empresa')->direccion, 0, 'L', 0, 0, '', '', true);
            //$pdf->MultiCell(70, 25, session('empresa')->provincia, 0, 'R', 0, 0, '', '', true);





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
                    'portabilidad indicándolo por escrito a %e '.session()->get('empresa')->direccion.' '.session()->get('empresa')->cpostal.' '.
                    session()->get('empresa')->poblacion.".\n";

            $html = str_replace('%e', session()->get('empresa')->nombre, $html);


            //$this->Write($h=0, $html, $link='', $fill=0, $align='J', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
            PDF::Write($h=0, $html, '', 0, 'J', true, 0, false, true, 0);

            PDF::Ln();
            PDF::SetFont('helvetica', 'R', 8);

            $pdf->Write($h=0, session()->get('empresa')->txtpie1, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
            $pdf->Write($h=0, session()->get('empresa')->txtpie2, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

            // $pdf->SetFont('helvetica', 'R', 8);
            // $pdf->MultiCell($w=190, $h, $pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), $border='', $align='R', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);


        });


                // set document information
        PDF::SetCreator(session('username'));
        PDF::SetAuthor($empresa->nombre);
        PDF::SetTitle('Factura');
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
