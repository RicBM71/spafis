<?php

namespace App\Http\Controllers\Tools;

use App\Models\Pacbono;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class CompartirBonoController extends Controller
{


    public static function locate(Request $request){

        $data = $request->validate([
            'bono'        => ['required','integer'],
            'paciente_id' => ['required','integer']
        ]);

        $ultimo_bono = Pacbono::where('paciente_id', $data['paciente_id'])
                                ->where('bono', '<', $data['bono'])
                                ->orderBy('bono', 'desc')
                                ->first();

        $pacientes = array();

        if ($ultimo_bono == '')
            return $pacientes;


        $bonos = Pacbono::with('paciente')->where('bono', $ultimo_bono->bono)
                                ->where('paciente_id', '<>', $data['paciente_id'])
                                ->get();

        foreach ($bonos as $bono){

            $pacientes[]=[
                'value' => $bono->paciente_id,
                'text'  => $bono->paciente->nombre.' '.$bono->paciente->apellidos,
            ];

        }

        return $pacientes;

    }

    public static function multiple(Request $request){

        $data = $request->validate([
            'pacbono_id'  => ['required','integer'],
            'pacientes'   => ['required','array']
        ]);

        $pacbono = Pacbono::findOrFail($data['pacbono_id']);
        $nuevo = collect($pacbono)->toArray();
        $nuevo['id']=null;

        foreach ($data['pacientes'] as $paciente_id){

            try {

                Pacbono::where('paciente_id', $paciente_id)
                        ->where('bono', $pacbono->bono)
                        ->firstOrFail();

            } catch (\Exception $e) {

                $nuevo['paciente_id']=$paciente_id;
                $nuevo['username'] = session('username');

                $reg = Pacbono::create($nuevo);
            }

        }


    }

    public static function submit(Request $request){

        $data = $request->validate([
            'pacbono_id' => ['required','integer'],
            'dest_paciente_id' => ['required','integer']
        ]);

        $pacbono = Pacbono::findOrFail($data['pacbono_id']);

        try {

            Pacbono::where('paciente_id', $data['dest_paciente_id'])
                    ->where('bono', $pacbono->bono)
                    ->firstOrFail();
            return abort(422, 'El bono ya ha sido asignado');

        } catch (\Exception $e) {

            $nuevo = collect($pacbono)->toArray();

            $nuevo['id']=null;
            $nuevo['paciente_id']=$data['dest_paciente_id'];

            $nuevo['username'] = session('username');

            $reg = Pacbono::create($nuevo);
        }


        //\Log::info($nuevo);


        if (request()->wantsJson()){
            //return response('ok',200);
            return $reg->load('paciente');
        }
    }
}
