<?php

namespace App\Http\Controllers\Tools;

use Carbon\Carbon;
use App\Models\Bono;
use App\Models\Cita;
use App\Models\Pacbono;
use App\Models\Tratamiento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AsignarBonoController extends Controller
{

    public function asignar(Pacbono $pacbono){

        $tratamiento = Tratamiento::findOrFail($pacbono->tratamiento_id);

        // busco citas pendientes sin bono asignado.
        $dt = Carbon::now()->subDays(365); // para evitar asignar antiguos, regalos por ejemplo.

        $citas = Cita::where('paciente_id', $pacbono->paciente_id)
                    ->where('tratamiento_id', $pacbono->tratamiento_id)
                    ->where('estado_id', '<=', 2)
                    //->where('importe', '>', 0)
                    ->where('fecha', '>=', $dt)
                    ->where('fecha', '<=', date('Y-m-d'))
                    ->whereNull('bono')
                    ->orderBy('fecha','asc')
                    ->get();

        foreach ($citas as $cita){

            if ($cita->fecha < $pacbono->fecha){
                // actualizo la fecha del bono
                $pacbono->fecha = $cita->fecha;
                Pacbono::where('bono', $pacbono->bono)->update(['fecha' => $cita->fecha]);
            }

            $data = consumosBono($pacbono, $tratamiento);

            if ($data != false){
                $cita->update([
                    'bono'      => $data['bono'],
                    'importe'   => $data['importe'],
                    'iva'       => $data['iva'],
                    'importe_ponderado'   => $data['importe_ponderado']
                ]);
            }

        }

        // // busco bonos disponibles.
        // $bonos = Bonpac::where('paciente_id', $cita->paciente_id)
        //                 ->where('tratamiento_id', $cita->tratamiento_id)
        //                 ->where('caducado', false)
        //                 ->orderBy('fecha')
        //                 ->get();

        // foreach ($bonos as $bono){

        // }

    }


}
