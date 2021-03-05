<?php

use Carbon\Carbon;
use App\Models\Cita;
use App\Models\Bloqueo;
use App\Models\Horario;

function getDecimal($valor, $dec=2){
    return number_format($valor,$dec, ",", ".");
}

function getCurrency($valor, $currency="€"){
    return number_format($valor,2, ",", ".")." ".$currency;
}

function getDecimalExcel($valor, $dec=2){
    return round($valor, $dec);
}

function getTelefonoFijo($t){

    return (Str::length($t) == 9) ? substr($t,0, 2).' '.substr($t,2, 3).' '.substr($t,5, 2).' '.substr($t,7, 2) : $t;

}

function getTelefonoMovil($t){

    return (Str::length($t) == 9) ? substr($t,0, 3).' '.substr($t,3, 2).' '.substr($t,5, 2).' '.substr($t,7, 2) : $t;


}

function getFecha($value)
{

    if (is_null($value)) return null;

    return Carbon::parse($value)->format('d/m/Y');

}

function getFechaHoraDia($value){
    return Carbon::parse($value)->isoFormat('dddd, D [de] MMMM[:] H:mm');
}

function getHora($value)
{

    if (is_null($value)) return null;

    return Carbon::parse($value)->format('H:mm');

}

function getEjercicio($fecha){
    return Carbon::parse($fecha)->format('Y');
}

function getIbanPrint($iban){

    $iban_print = '';

    $iban = str_split($iban,4);

    foreach ($iban as $e){
        $iban_print .= $e.' ';
    }

    return $iban_print;

}


function sumarDiasAFecha($fecha, $dias){

    //$fecha = fechaAMysql($fecha);

    $fecha_tope = date("Y-m-d", strtotime("$fecha + $dias days"));

    //$fecha_tope = date('Y-m-d',$fecha + ($dias *24*60*60));
    return $fecha_tope;
}

function setImporteGr($peso_gr,$importe){
    return $peso_gr != 0 ? round($importe / $peso_gr, 2) : 0;
}

function esRoot(){
    return auth()->user()->hasRole('Root');
}

function esAdmin(){
    return auth()->user()->hasRole('Admin');
}

function esSupervisor(){
    return (auth()->user()->hasRole('Supervisor') || auth()->user()->hasRole('Admin'));
}

function esGestor(){
    return auth()->user()->hasRole('Gestor');
}


function hasHardDel(){
    return auth()->user()->hasPermissionTo('delete');
}

function hasContact(){
    return auth()->user()->hasPermissionTo('contact');
}

function hasClinic(){
    return auth()->user()->hasPermissionTo('clinic');
}


function hasUpload(){
    return auth()->user()->hasPermissionTo('upload');
}

function hasUsers(){
    return auth()->user()->hasPermissionTo('userr');
}

function hasPrecios(){
    return auth()->user()->hasPermissionTo('editimp');
}

function hasCobros(){
    return auth()->user()->hasPermissionTo('cobros');
}


function esPropietario($obj)
{
    return ($obj->username == auth()->user()->username && Carbon::today()->format('Y-m-d')== Carbon::parse($obj->created_at)->format('Y-m-d'))
         ? true : false;
}
/**
 * @param integer $ejercicio
 * @param integer $trimestre
 * @return array ['d','h']
 */
function trimestre($ejercicio,$trimestre){

    $m = (3 * $trimestre) - 2;

    return [
        'd' => Carbon::parse($ejercicio.'-'.$m.'-01')->startOfQuarter()->format('Y-m-d'),
        'h' => Carbon::parse($ejercicio.'-'.$m.'-01')->endOfQuarter()->format('Y-m-d')
    ];

    //h 3x1 - 2

    // echo Carbon::parse('2019-01-01')->endOfQuarter()->format('Y-m-d');
    // echo Carbon::parse('2019-04-01')->endOfQuarter();
    // echo Carbon::parse('2019-07-01')->endOfQuarter();
    // echo Carbon::parse('2019-10-01')->endOfQuarter();
}

function totalAlbalin($data){

    $t = 0;
    foreach ($data as $row){
        $t =+ $row['importe_venta'];
    }

    return $t;

}

function selectHoras($frecuencia = 30){

    $dt = Carbon::create(2000, 1, 1, 9, 0, 0);

    $limite = Carbon::create(2000, 1, 1, 20, 0, 0);
    $limite->subMinutes($frecuencia);

    $horas=array();

    while ($dt->toTimeString() <= $limite){

        $h = $dt->format('H:i');

        if ($h >= "15:00" && $h <= "16:00"){
            $dt->addMinutes($frecuencia);
            continue;
        }

        $horas[]=['text'=> $h, 'value'=>$h];

        $dt->addMinutes($frecuencia);
    }

    return $horas;
}

/**
 * Undocumented function
 *
 * @param Object $bono
 * @param Object $fecha instancia de Carbon
 * @return void
 */
function bonoCaducado($bono, $fecha){

    if ($bono->caducidad == 0) return false; // no caduca

    $bono_fecha = Carbon::parse($bono->fecha);

    $dias = $fecha->diffInDays($bono_fecha);

    $dias_restan = $bono->caducidad - $dias;

    if ($dias > $bono->caducidad){ // está caducado
        // actualizamos la tabla
        $bono->update(['caducado'=>true]);

        return true;
    }

    return false;

}

function consumosBono($bono, $tratamiento){

    $tratamiento->load('iva');

    $consumos_bono = Cita::where('bono', $bono->bono)
                            ->where('estado_id','<>', 4)
                            ->orderBy('fecha')
                            ->get();

    $importe_bono_citas = $consumos_bono->sum('importe');


    $sesiones_consumidas = $consumos_bono->where('fecha','>=',$bono->fecha)->count();


    if ($sesiones_consumidas >= $bono->sesiones){ // está consumido
        // de momento, no lo caduco por si hay anulación.
        //$this->caducarBono($bono->bono);
        return false;
    }
    else {

        if ($importe_bono_citas > 0) // no hay cita asignada luego proponemos importe
            $imp_bono = 0;
        else
            $imp_bono = $bono->importe;

            $importe_ponderado = $bono->sesiones <> 0 ? round($bono->importe / $bono->sesiones, 2) : 0;

            $valores = array('bono'      => $bono->bono,
                             'importe'   => $imp_bono,
                             'iva'       => $tratamiento->iva->importe,
                             'importe_ponderado'   => $importe_ponderado);

        return $valores;
    }

    return false;
}

function getWhatsAppCita($cita){

    if (strlen($cita->paciente->telefonom) != 9) return false;

    $dt = Carbon::parse($cita->fecha.' '.$cita->hora);

    $fecha = $dt->isoFormat('dddd, D [de] MMMM [a las] H:mm');

    //$fecha = $dt->format('l j \\de F \\a \\las H:i');
    $fecha = '*'.str_replace(' ', '%20', $fecha).'*';

    $ws="https://api.whatsapp.com/send?phone=34".$cita->paciente->telefonom."&text=Hola%20".mb_convert_case(trim($cita->paciente->nombre),MB_CASE_TITLE, "UTF-8").",%20tienes%20cita%20el%20".$fecha.".%20Si%20no%20puedes%20acudir,%20te%20agradeceríamos%20que%20nos%20lo%20comunicases%20con%20antelación.%20Gracias.";

    return $ws;
	//$str.='<a target="_blank" id="cmd_ws" class="btn btn-success btn-sm" href="'.$ws.'"><span class="glyphicon glyphicon-phone"></span> Whatsapp</a>';

}

function mes($m){
    $meses = [
        '1' => 'Enero',
        '2' => 'Febrero',
        '3' => 'Marzo',
        '4' => 'Abril',
        '5' => 'Mayo',
        '6' => 'Junio',
        '7' => 'Julio',
        '8' => 'Agosto',
        '9' => 'Septiembre',
        '10' => 'Octubre',
        '11' => 'Noviembre',
        '12' => 'Diciembre'
    ];

    return $meses[$m];
}


// function selectHoras($frecuencia = 30){

//     $dt = Carbon::create(2000, 1, 1, 9, 0, 0);

//     $limite = Carbon::create(2000, 1, 1, 20, 0, 0);
//     $limite->subMinutes($frecuencia);

//     $horas=array();

//     while ($dt->toTimeString() <= $limite->toTimeString()){

//         $h = $dt->toTimeString();

//         if ($h >= "15:00:00" && $h <= "16:00:00")
//             continue;

//         $horas[]=['text'=> $h, 'value'=>$h];

//         $dt->addMinutes($frecuencia);
//     }

//     return $horas;
// }
