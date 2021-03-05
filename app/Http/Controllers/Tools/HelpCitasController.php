<?php

namespace App\Http\Controllers\Tools;

use Carbon\Carbon;
use App\Models\Cita;
use App\Models\Pacbono;
use App\Models\Paciente;
use App\Models\Tratamiento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HelpCitasController extends Controller
{

    public function valorar(Request $request){

        $data = $request->validate([
            'fecha'          => ['required', 'date'],
            'paciente_id'    => ['required', 'integer'],
            'tratamiento_id' => ['nullable', 'integer'],
            'facultativo_id' => ['required', 'integer'],
            'hora'           => ['nullable']
        ]);

        //$this->calcularprecio($data['fecha'], $data['paciente_id'], $data['tratamiento_id'],true);

        $precio =  $this->getPrecioTratamiento($data['paciente_id'],$data['tratamiento_id'],$data['fecha']);

        return [
            'precios'   => $precio,
            'horas' => Cita::selectHorasFacultativo($data['fecha'], $data['facultativo_id'], $data['hora'])
        ];

    }

    /**
	 *
	 * Calculo de precios por paciente y tratamiento
	 * @param unknown_type $paciente
	 * @param unknown_type $tratamiento
	 * @param unknown_type $fecha DD/MM/YYYY
	 */
	private function getPrecioTratamiento($paciente_id,$tratamiento_id,$fecha){

		if ($tratamiento_id==null) return 0;

        $paciente = Paciente::findOrFail($paciente_id);

        $tratamiento = Tratamiento::with('iva')->findOrFail($tratamiento_id);

        $precios = $this->bonoPaciente($paciente_id, $tratamiento, $fecha);

        if ($precios != false)  // hay bono disponible.
            return $precios;

		$paciente->tarifa_reducida == true ? $base = $tratamiento->importe_reducido : $base = $tratamiento->importe;

		// calcular precio en base a descuentos
		if ($paciente->descuento <> 0){ // hay descuento
			if ($paciente->porcentual){ // es %
				$dto = round($base * (float) $paciente->descuento / 100, 2);
				$precio = $base - $dto;
			}
			else{ // es directo
				$precio = $base - (float) $paciente->descuento;
			}
		}
		else{
			$precio = $base;
		}

       // $precio = round($base + ($base * $tratamiento->iva->importe / 100), 2);

        //return array('precio'=>$precio,'iva'=>$tratamiento->iva->importe);

        return array('bono'     => null,
                    'importe'   => $precio,
                    'iva'       => $tratamiento->iva->importe,
                    'importe_ponderado'   => $precio);



	}

	public function bonoPaciente($paciente_id,$tratamiento, $fecha){

        $fecha = Carbon::parse($fecha);

        $kk = $fecha->format('d/m/Y');

        if ($fecha->greaterThan(Carbon::today()))
            return false;

		$bonos = Pacbono::where('paciente_id', $paciente_id)
						->where('tratamiento_id', $tratamiento->id)
						->where('caducado', false)
						->orderBy('fecha')
						->get();

		foreach ($bonos as $bono){ // leemos los bonos del paciente

            if (bonoCaducado($bono, $fecha))
                continue;
            else{ // comprobamos si hay sesiones libres
                $consumos = consumosBono($bono, $tratamiento);
                if ($consumos !== false)
                    return $consumos;

            }
		}
		return false;
	}

    public function huecos(Request $request){

        $data = $request->validate([
            'fecha'          => ['required','date'],
            'facultativo_id' => ['required','integer'],
            'tratamiento_id' => ['required', 'integer'],
            'paciente_id'    => ['required', 'integer'],
            'hora'           => ['nullable']
        ]);

        if (request()->wantsJson())
            return[
                'horas' => Cita::selectHorasFacultativo($data['fecha'], $data['facultativo_id'], $data['hora']),
                'precios' => $this->getPrecioTratamiento($data['paciente_id'],$data['tratamiento_id'],$data['fecha'])
            ];
    }

    public function anticipo(Request $request){


        $data = $request->validate([
            'paciente_id'   => ['required','integer'],
            'tratamiento_id'   => ['required','integer'],
        ]);

		$bono = Pacbono::with('tratamiento')->where('paciente_id', $data['paciente_id'])
						->where('tratamiento_id', $data['tratamiento_id'])
						->where('caducado', false)
						->orderBy('fecha','desc')
                        ->get();
        if ($bono->count() == 0)
            return abort(404,'No hay bono que anticipar');

        $bono = $bono->first();

        if (Cita::where('bono', $bono->bono)
                ->where('estado_id', '<>', 4)
                ->get()
                ->count() > 0)
            return abort(404,'No hay bono que anticipar **');

        if ($bono == null) return abort(404,'No hay bono que anticipar');

        return [
            'bono'     => $bono->bono,
            'importe'       => $bono->importe,
            'iva'           => $bono->tratamiento->iva->importe,
            'importe_ponderado'   => round($bono->importe / $bono->sesiones,2)
        ];
    }

    public function vendibles(Request $request){

        $data = $request->validate([
            'paciente_id'   => ['required','integer']
        ]);

        if (request()->wantsJson())
            return [
                'items'     => Cita::with('tratamiento')
                                    ->where('area_id',9999)
                                    ->where('paciente_id', $data['paciente_id'])
                                    ->where('estado_id', 1)
                                    ->get(),
                'productos' => Tratamiento::selVendibles()
            ];

    }

    public function facturables(Request $request){

        $data = $request->validate([
            'paciente_id'   => ['required','integer'],
            'ejercicio'   => ['required','integer'],
        ]);

        if (request()->wantsJson())
            return [
                'items'     => Cita::with('tratamiento')
                                    ->whereYear('fecha', $data['ejercicio'])
                                    ->where('paciente_id', $data['paciente_id'])
                                    ->whereNull('factura_id')
                                    //->where('importe','>',0)
                                    ->get(),
            ];

	}


}
