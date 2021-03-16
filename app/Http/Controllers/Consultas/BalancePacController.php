<?php

namespace App\Http\Controllers\Consultas;

use App\Models\Area;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BalancePacController extends Controller
{
    public function index(Paciente $paciente){

        if (request()->wantsJson())
            return [
                'paciente' => $paciente,
                'areas'    => Area::selAreas(),
            ];

    }

    public function submit(Request $request){

        $data = $request->validate([
            'fechad'      => ['required','date'],
            'fechah'      => ['required','date'],
            'paciente_id' => ['required','integer'],
            'area_id'     => ['required','integer'],
        ]);

        if (request()->wantsJson())
            return $this->procesar($data);

    }

    private function procesar($data){

        $rows = DB::table('cobros')
                ->select(DB::raw('cobros.fecha, citas.fecha AS fecha_cita, citas.hora, tratamientos.nombre, fpagos.nombre AS fpago, cobros.autorizacion, cobros.id, citas.importe, cobros.importe AS cobrado'))
                    ->join('citas','citas.id','=','cita_id')
                    ->join('tratamientos','tratamientos.id','=','tratamiento_id')
                    ->join('fpagos','fpagos.id','=','fpago_id')
                    ->where('cobros.paciente_id', $data['paciente_id'])
                    ->whereDate('cobros.fecha', '>=', $data['fechad'])
                    ->whereDate('cobros.fecha', '<=', $data['fechah'])
                    ->orderBy('fecha','desc')
                    ->get();

        return [
            'items' => $rows,
            'total_cobrado' => $rows->sum('importe')
        ];

    }
}
