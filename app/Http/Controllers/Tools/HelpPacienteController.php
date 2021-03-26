<?php

namespace App\Http\Controllers\Tools;

use App\Models\Pacbono;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HelpPacienteController extends Controller
{

    public static function index(Request $request){

        $data = $request->validate([
            'search' => ['required','string']
        ]);

        //$cadena = 'ri,ba';

        if (request()->wantsJson()){


            return Paciente::select(DB::raw('id AS value, CONCAT(nombre," ",apellidos) AS text'))
                    ->razon($data['search'])
                    ->orderBy('nombre', 'asc')
                    ->orderBy('apellidos', 'asc')
                    ->get();
        }
    }

    /**
     * Obtener último bono activo
     *
     * @param [type] $paciente_id
     * @return void
     */
    public static function bono($paciente_id){


        if (request()->wantsJson()){

            $bono = Pacbono::where('paciente_id', $paciente_id)
						->where('caducado', false)
						->orderBy('fecha','desc')
						->first();


            return $bono;
        }

    }

    // public static function get($cadena){


    //     if (request()->wantsJson()){


    //         return Paciente::select(DB::raw('id AS value, CONCAT(nombre," ",apellidos) AS text'))
    //                 ->razon($cadena)
    //                 ->orderBy('nombre')
    //                 ->orderBy('apellidos')
    //                 ->get();
    //     }
    // }


}
