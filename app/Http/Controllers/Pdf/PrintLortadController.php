<?php

namespace App\Http\Controllers\Pdf;

use PDF;
use Carbon\Carbon;
use App\Models\Paciente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PrintLortadController extends Controller
{

    protected $paciente;

    public function print(Paciente $paciente){

        $file = false;

        $empresa  = session()->get('empresa');

        ob_end_clean();

        $this->setPrepararFormulario($empresa);

        $this->paciente = $paciente;

        $this->formulario();

        PDF::Close();

        if ($file){
            if (file_exists(storage_path('pdfdocs'))==false)
                mkdir(storage_path('pdfdocs'), '0755');

            PDF::Output(storage_path('pdfdocs/PD'.$paciente->id.'.pdf'), 'F');

        }
        else{
            return PDF::Output('PD'.$paciente->id.'.pdf','I');
        }

        PDF::reset();
    }

     /**
     *
     * Formulario de factura de recuperación de compras.
     *
     */
    private function formulario()    {


        PDF::AddPage();

        $this->printBody();

       // $this->impNotas();

    }


    /**
     *
     * @param Model Albalins
     *
     */
    private function printBody() {

        PDF::SetFont('helvetica', 'R', 10, '', false);


        $txt = "   En aras a dar cumplimiento al Reglamento (UE) 2016/679 del Parlamento Europeo y del Consejo, de 27 de abril de 2016 y siguiendo las Recomendaciones emitidas por la Agencia Española de Protección de Datos,\n";


        PDF::Write($h=0, $txt, '', 0, 'J', true, 0, false, true, 0);
        PDF::Ln();

        PDF::SetFont('helvetica', 'R', 9, '', false);

        $txt ="   - Los datos de carácter personal solicitados y facilitados por Usted, son incorporados a un fichero de titularidad privada cuyo responsable y único destinatario es %e.";
        $txt = str_replace('%e',session('empresa')->razon,$txt)."\n";
        PDF::MultiCell(10, 5,  '', '', 'J', 0, 0, '', '', true,0,false,true,50,'T',false);
        PDF::MultiCell(160, 5,  $txt, '', 'J', 0, 1, '', '', true,0,false,true,50,'T',false);

        PDF::MultiCell(10, 4,  '', '', 'J', 0, 1, '', '', true,0,false,true,4,'T',false);

        $txt ="   - Solo serán solicitados aquellos datos estrictamente necesarios para prestar adecuadamente el servicio sanitario, pudiendo ser necesario recoger datos de contacto de terceros, tales como representantes legales, tutores, o personas a cargo designadas por los mismos.";
        $txt = str_replace('%e',session('empresa')->razon,$txt);
        PDF::MultiCell(10, 5,  '', '', 'J', 0, 0, '', '', true,0,false,true,50,'T',false);
        PDF::MultiCell(160, 5,  $txt."\n", '', 'J', 0, 1, '', '', true,0,false,true,50,'T',false);
        PDF::MultiCell(10, 4,  '', '', 'J', 0, 1, '', '', true,0,false,true,4,'T',false);

        $txt ="   - Como profesionales de la sanidad, garantizamos que todos los datos recogidos cuentan con el compromiso de confidencialidad y cumplen con las medidas de seguridad establecidas legalmente. Bajo ningún concepto susodichos datos serán cedidos o tratados por terceras personas -físicas o jurídicas- sin el previo consentimiento del paciente, tutor o representante legal, salvo en aquellos casos en los que fuere imprescindible para la correcta prestación del servicio.\n";
        $txt = str_replace('%e',session('empresa')->razon,$txt);
        PDF::MultiCell(10, 5,  '', '', 'J', 0, 0, '', '', true,0,false,true,50,'T',false);
        PDF::MultiCell(160, 5,  $txt, '', 'J', 0, 1, '', '', true,0,false,true,50,'T',false);
        PDF::MultiCell(10, 4,  '', '', 'J', 0, 1, '', '', true,0,false,true,4,'T',false);

        $txt ="   - Una vez finalizada la relación entre la empresa y el paciente, los datos serán archivados y conservados durante un periodo de tiempo mínimo de 5 años desde la última visita, tras lo cual, podrán continuar archivados, o en su defecto, serán devueltos íntegramente al paciente o autorizado legal, o destruidos por procedimientos seguros que garanticen la confidencialidad de la información sensible.";
        $txt = str_replace('%e',session('empresa')->razon,$txt)."\n";
        PDF::MultiCell(10, 5,  '', '', 'J', 0, 0, '', '', true,0,false,true,50,'T',false);
        PDF::MultiCell(160, 5,  $txt, '', 'J', 0, 1, '', '', true,0,false,true,50,'T',false);
        PDF::MultiCell(10, 4,  '', '', 'J', 0, 1, '', '', true,0,false,true,4,'T',false);

        //PDF::Ln();

        $txt ="   - Los datos facilitados serán incluidos en el tratamiento denominado Pacientes de %e, con la finalidad de gestionar el tratamiento médico, emitir facturas, gestiones relacionadas con el paciente, contacto, manifiestos de consentimiento, etc.";
        $txt = str_replace('%e',session('empresa')->razon,$txt)."\n";
        PDF::MultiCell(10, 5,  '', '', 'J', 0, 0, '', '', true,0,false,true,50,'T',false);
        PDF::MultiCell(160, 5,  $txt, '', 'J', 0, 1, '', '', true,0,false,true,50,'T',false);
        PDF::MultiCell(10, 4,  '', '', 'J', 0, 1, '', '', true,0,false,true,4,'T',false);


        $txt ="   - Puede ejercitar los derechos de acceso, rectificación, cancelación, limitación, oposición y portabilidad, indicándolo por escrito a %e, con domicilio en: ".session('empresa')->direccion." ".session('empresa')->poblacion.".";
        $txt = str_replace('%e',session('empresa')->razon,$txt)."\n";
        PDF::MultiCell(10, 5,  '', '', 'J', 0, 0, '', '', true,0,false,true,50,'T',false);
        PDF::MultiCell(160, 5,  $txt, '', 'J', 0, 1, '', '', true,0,false,true,50,'T',false);
        PDF::MultiCell(10, 4,  '', '', 'J', 0, 1, '', '', true,0,false,true,4,'T',false);


        $txt ="   - Los datos personales facilitados podrán ser cedidos por %e a las entidades que prestan servicios a la misma.";
        $txt = str_replace('%e',session('empresa')->razon,$txt)."\n";
        PDF::MultiCell(10, 5,  '', '', 'J', 0, 0, '', '', true,0,false,true,50,'T',false);
        PDF::MultiCell(160, 5,  $txt, '', 'J', 0, 1, '', '', true,0,false,true,50,'T',false);
        PDF::MultiCell(10, 4,  '', '', 'J', 0, 1, '', '', true,0,false,true,4,'T',false);

        PDF::SetFont('helvetica', 'R', 10, '', false);
        $txt = "   Además de las cláusulas anteriores le solicitamos el consentimiento para:\n";
        PDF::Write($h=0, $txt, '', 0, 'J', true, 0, false, true, 0);
        PDF::Ln();

        PDF::SetFont('helvetica', 'R', 9, '', false);

        $txt ="   [  ] ACEPTO que %e me remita comunicaciones informativas a través de e-mail, SMS, o sistemas de mensajería instantánea como Whatsapp, con el objetivo de mantenerme informado/a del desarrollo de las actividades propias del servicio contratado, enviarme recordatorios de mis citas, así como remitirme informes relativos a la prestación asistencial acordada entre ambas partes.";
        $txt = str_replace('%e',session('empresa')->razon,$txt)."\n";
        PDF::MultiCell(10, 5,  '', '', 'J', 0, 0, '', '', true,0,false,true,50,'T',false);
        PDF::MultiCell(160, 5,  $txt, '', 'J', 0, 1, '', '', true,0,false,true,50,'T',false);
        PDF::MultiCell(10, 4,  '', '', 'J', 0, 1, '', '', true,0,false,true,4,'T',false);

        $txt ="   [  ] ACEPTO Y SOLICITO EXPRESAMENTE, la recepción de comunicaciones comerciales por vía electrónica (e-mail, Whatsapp, bluetooth, SMS), por parte de %e, sobre productos, servicios, promociones y ofertas de mi interés";
        $txt = str_replace('%e',session('empresa')->razon,$txt)."\n";
        PDF::MultiCell(10, 5,  '', '', 'J', 0, 0, '', '', true,0,false,true,50,'T',false);
        PDF::MultiCell(160, 5,  $txt, '', 'J', 0, 1, '', '', true,0,false,true,50,'T',false);
        PDF::MultiCell(10, 4,  '', '', 'J', 0, 1, '', '', true,0,false,true,4,'T',false);

        PDF::MultiCell(10, 4,  '', '', 'J', 0, 1, '', '', true,0,false,true,4,'T',false);


        PDF::SetFont('helvetica', 'R', 10, '', false);
        $f = Carbon::today();
        $txt = "   En ".session('empresa')->poblacion.', a '.$f->isoFormat('D [de] MMMM [de] YYYY[.]');

        PDF::Write($h=0, $txt, '', 0, 'L', true, 0, false, true, 0);

        PDF::Ln();
        PDF::Ln();
        PDF::Ln();
        PDF::Ln();
        PDF::Ln();
        PDF::Ln();

        $txt = "Firma";
        PDF::Write($h=0, $txt, '', 0, 'C', true, 0, false, true, 0);

        $txt = $this->paciente->nom_ape;
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

            $pdf->SetFont('helvetica', 'R', 9, '', false);
            $y = 10;
            $pdf->SetXY(4, 10);
            $pdf->Write($h=0, 'Tf.: '.getTelefonoFijo(session('empresa')->telefono1), '', 0, 'R', true, 0, false, true, 0);
            $pdf->SetXY(4, 16);
            $pdf->Write($h=0, 'WhatsApp: '.getTelefonoMovil(session('empresa')->telefono2), '', 0, 'R', true, 0, false, true, 0);
            $pdf->SetXY(4, 22);
            $pdf->Write($h=0, session('empresa')->email, '', 0, 'R', true, 0, false, true, 0);

            $pdf->Ln();
            $pdf->SetFont('helvetica', 'RB', 11, '', false);
            $txt = "Consentimiento Informado";
            //$pdf->SetXY(4, 36);
            $pdf->Write($h=0, $txt, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);


        });

        PDF::setFooterCallback(function($pdf) {


            // $f = str_replace('storage', 'public', '/storage/logos/sello.png');

            // $file = '@'.(Storage::get($f));
            // $pdf->setJPEGQuality(75);
            // $pdf->Image($file, $x='140', $y='220', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false);



            PDF::Ln();
            PDF::SetFont('helvetica', 'R', 7);

            $pdf->Write($h=0, session()->get('empresa')->txtpie1, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
            $pdf->Write($h=0, session()->get('empresa')->txtpie2, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);



        });


                // set document information
        PDF::SetCreator(session('username'));
        PDF::SetAuthor($empresa->nombre);
        PDF::SetTitle('Lortad');
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
        PDF::SetMargins(13, 40, PDF_MARGIN_RIGHT);
        PDF::SetHeaderMargin(PDF_MARGIN_HEADER);
        //PDF::SetFooterMargin(PDF_MARGIN_FOOTER);
        PDF::SetFooterMargin(14);

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
