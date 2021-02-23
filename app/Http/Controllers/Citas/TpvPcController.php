<?php

namespace App\Http\Controllers\Citas;

use Carbon\Carbon;
use App\Models\Tpv;
use App\Models\TpvMov;
use App\Models\Empresa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TpvPcController extends Controller
{
    public function index($empresa_id, $tpv_id, $importe, $paciente_id){

        $tpv = Tpv::findOrfail($tpv_id);

        return view('tpv', compact('empresa_id','importe','paciente_id','tpv'));

    }

    public function store(Request $request){

        $data = $request->validate([
            'xml'           => ['required','string'],
            'empresa_id'    => ['required','integer'],
            'paciente_id'   => ['required','integer'],
            'tpv_id'        => ['required','integer'],
        ]);

//        \Log::info($data['xml']);

        $xml = simplexml_load_string($data['xml']);

        $json = json_encode($xml);

        $val = json_decode($json,TRUE);

        $autenticadoPorPin=null;
        $resultado = collect($val['resultadoOperacion']);


        if ($resultado->get('autenticadoPorPin') == 'TRUE'){
            $auth = $resultado->get('Literales');
            $autenticadoPorPin = $auth['autenticadoPorPin'];
        }

        $reg = TpvMov::create([
            'empresa_id'        => $data['empresa_id'],
            'fecha'             => date('Y-m-d'),
            'paciente_id'       => $data['paciente_id'],
            'tpv_id'            => $data['tpv_id'],
            'tipo_pago'         => $resultado->get('tipoPago'),
            'moneda'            => $resultado->get('moneda'),
            'comercio'          => $resultado->get('comercio'),
            'pedido'            => $resultado->get('pedido'),
            'fecha_operacion'   => Carbon::parse($resultado->get('fechaOperacion')),
            'titular_tarjeta'   => $resultado->get('titularTarjeta'),
            'tarjeta'           => $resultado->get('tarjetaClienteRecibo'),
            'estado'            => $resultado->get('estado'),
            'codigo_respuesta'  => $resultado->get('codigoRespuesta'),
            'importe'           => $resultado->get('importe'),
            'autenticado'       => $autenticadoPorPin
        ]);

            $empresa = Empresa::findOrfail($data['empresa_id']);

       //   if (request()->wantsJson())
            $res = [
                'comercio'  => $reg->comercio,
                'titular'   => $reg->titular_tarjeta,
                'tarjeta'   => $reg->tarjeta,
                'caducidad' => $reg->caducidad,
                'codigo_respuesta' => $reg->codigo_respuesta,
                'pedido'    => $reg->pedido,
                'fecha'     => getFecha($reg->fecha_operacion),
                'hora'      => getHora($reg->fecha_operacion),
                'importe'   => getCurrency($reg->importe),
                'telefono'  => $empresa->telefono1
            ];

        return (($res));

    }

    // Resultado Operacion :
    // <Operaciones version="6.0">
    // <resultadoOperacion>
    // <tipoPago>PAGO</tipoPago>
    // <importe>21.30</importe>
    // <moneda>978</moneda>
    // <tarjetaClienteRecibo>************4014</tarjetaClienteRecibo>
    // <tarjetaComercioRecibo>************4014</tarjetaComercioRecibo>
    // <marcaTarjeta>1</marcaTarjeta>
    // <caducidad>0000</caducidad>
    // <comercio>025256579</comercio>
    // <terminal>1</terminal>
    // <pedido>10511</pedido>
    // <tipoTasaAplicada>CRED</tipoTasaAplicada>
    // <identificadorRTS>070014201117110907251001</identificadorRTS>
    // <fechaOperacion>2020-11-17 11:09:07.0</fechaOperacion>
    // <estado>F</estado>
    // <resultado>Autorizada</resultado>
    // <codigoRespuesta>639266</codigoRespuesta>
    // <firma>EFB0BFE7F267D1AA9BB1492C51EF9D6B67D59E3B</firma>
    // <operacionemv>true</operacionemv>
    // <resverificacion>0000000000</resverificacion>
    // <conttrans>000002</conttrans>
    // <sectarjeta>00</sectarjeta>
    // <idapp>A0000000031010</idapp>
    // <operContactLess>TRUE</operContactLess>
    // <autenticadoPorPin>TRUE</autenticadoPorPin>
    // <Literales>
    // <autenticadoPorPin>OPERACION AUT MOVIL. FIRMA NO NECESARIA.</autenticadoPorPin>
    // </Literales>
    // </resultadoOperacion>
    // </Operaciones>


}

