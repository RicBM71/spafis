<?php

namespace App\Http\Controllers\Facturas;

use Carbon\Carbon;
use App\Models\Cita;
use App\Models\Factlin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FactlinsController extends Controller
{
    public function show($factura_id){
        if (request()->wantsJson())
            return [
                'lineas' => Factlin::where('factura_id', $factura_id)->get(),
                'total'  => Factlin::totalFactura($factura_id)
            ];
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'factura_id' => ['required','integer'],
            'concepto'   => ['nullable'],
            'iva'        => ['required','numeric'],
            'importe'    => ['required','numeric'],
        ]);

        $data['username']   = session()->get('username');


        $reg = Factlin::create($data);

        if (request()->wantsJson())
            return [
                'message' => 'EL registro ha sido creado'
            ];
    }

    public function destroy(Factlin $factlin)
    {

       // $this->authorize('delete', $factura);

       $data = [
           'factura_id' => null,
           'username'   => session('username'),
           'updated_at' => Carbon::now()
       ];

        Cita::where('factura_id', $factlin->factura_id)->update($data);

        $factlin->delete();


    }

    public function facturar(Request $request){

        $data = $request->validate([
            'citas' => 'required',
            'factura' => 'required'
        ]);

        $citas = collect($data['citas']);

        $factura = $data['factura'];

        $total = 0;
        foreach ($citas as $cita){
            Cita::where('id', $cita['id'])
                    ->update([
                        'factura_id' => $factura['id'],
                        'updated_at' => Carbon::now(),
                        'username'   => session('username')
                    ]);

            $total += $cita['importe'];
        }

        $data = [
            'factura_id' => $factura['id'],
            'concepto'   => 'TRATAMIENTOS DE FISIOTERAPIA',
            'iva'        => 0,
            'importe'    => $total,
            'username'   => session()->get('username')
        ];

        $reg = Factlin::create($data);

    }


}
