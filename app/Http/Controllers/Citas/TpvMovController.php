<?php

namespace App\Http\Controllers\Citas;

use App\Models\Tpv;
use App\Models\TpvMov;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TpvMovController extends Controller
{

    public function update(Request $request){

        $data = $request->validate([
            'paciente_id'  => ['required', 'integer'],
            'importe'      => ['required', 'numeric'],
        ]);

        $recibo = TpvMov::where('paciente_id',$data['paciente_id'])
                          ->where('procesado', false)
                          ->first();

        if ($recibo == null)
            return response(202, 'No hay nada que procesar');

        if ($recibo->importe != $data['importe'])
            return abort(411, 'Importes sin procesar '.$recibo->importe.' difiere de '.$data['importe']);

        $recibo->update([
            'username'  => session('username'),
            'procesado' => true
        ]);

        if (request()->wantsJson())
            return [
               'recibo' => $recibo
            ];

    }

    public function recibo($paciente_id, $fecha){


        $recibo = TpvMov::where('paciente_id', $paciente_id)
                        ->whereDate('fecha', $fecha)
                        ->first();

        if ($recibo == null){
            return abort('404');
        }


        $tpv = Tpv::findOrFail($recibo->tpv_id);

        $telefono1  = session('empresa')->telefono1;

        return view('tarjeta', compact('recibo','tpv', 'telefono1'));


    }
}
