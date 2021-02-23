<?php

namespace App\Http\Controllers\Facturas;

use App\Models\Area;
use App\Models\Cita;
use App\Models\Cobro;
use App\Models\Fpago;
use App\Models\Factlin;
use App\Models\Factura;
use App\Models\Paciente;
use Illuminate\Http\Request;
use App\Scopes\EmpresaActivaScope;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class FacturacionController extends Controller
{
    public function index(){

        if (!esAdmin()){
            return abort(403, 'No dispone de permisos!');
        }

        if (request()->wantsJson())
            return [
                'areas'  => Area::selAreas(),
                'fpagos' => Fpago::selFpagos(),
            ];

    }

    public function submit(Request $request){

        if (!esAdmin()){
            return abort(403, 'No dispone de permisos!');
        }

        $data = $request->validate([
            'fecha'     => ['required','date'],
            'fecha_d'   => ['required','date'],
            'fecha_h'   => ['required','date'],
            'area_id'   => ['required','integer'],
            'fpago_id'  => ['required','integer'],
            'accion'    => ['required'],
        ]);

        if ($data['accion'] == 'F')
            $this->facturar($data);
        else
            $this->desfacturar($data);

        return response('Proceso finalizado!',200);


    }

    private function facturar($data){


        $cobros = DB::table('cobros')
                    ->select('cobros.paciente_id', 'citas.id', 'cobros.importe')
                    ->join('citas','citas.id','=','cita_id')
                    ->where('cobros.empresa_id', session('empresa')->id)
                    ->where('cobros.area_id', $data['area_id'])
                    ->where('fpago_id', $data['fpago_id'])
                    ->whereDate('cobros.fecha','>=', $data['fecha_d'])
                    ->whereDate('cobros.fecha','<=', $data['fecha_h'])
                    ->whereNull('factura_id')
                    ->whereNull('mutua_id')
                    ->orderBy('paciente_id','asc')
                    ->get();

        $paciente_ant = $cobros[0]->paciente_id;
        $total_factura = 0;
        $citas=array();

        $contador = Factura::whereYear('fecha', getEjercicio($data['fecha']))
                            ->where('serie', 'A')
                            ->orderBy('factura','desc')
                            ->first();
        if ($contador == '')
            $num_factura = 0;
        else
            $num_factura = $contador->factura + 1;

        foreach ($cobros as $cobro){

            if ($paciente_ant <> $cobro->paciente_id){

                $num_factura++;

                $this->crearFactura($paciente_ant, $total_factura, $data, $citas, $num_factura);

                $paciente_ant = $cobro->paciente_id;
                $total_factura = $total_sesiones = 0;
                $citas=array();
            }

            $total_factura += $cobro->importe;

            $citas[]=$cobro->id;


        }

    }

    private function crearFactura($paciente_id, $total_factura, $data, $citas, $num_factura){


        $paciente = Paciente::findOrFail($paciente_id);

        $factura['razon']     = $paciente->nom_ape;
        $factura['direccion'] = $paciente->direccion;
        $factura['cpostal']   = $paciente->cpostal;
        $factura['poblacion'] = $paciente->poblacion;
        $factura['provincia'] = $paciente->provincia;
        $factura['cif']       = $paciente->dni;
        $factura['paciente_id']       = $paciente->id;

        $factura['ejercicio']    = getEjercicio($data['fecha']);

        $factura['fecha'] = $data['fecha'];
        $factura['factura']  = $num_factura;
        $factura['serie']    = 'A';

        $factura['fpago_id'] = $data['fpago_id'];

        $factura['empresa_id'] =  session()->get('empresa')->id;
        $factura['username']   = session()->get('username');

        $f = Factura::create($factura);

        $faclin = [
            'factura_id' => $f->id,
            'concepto'   => count($citas).' TRATAMIENTOS DE FISIOTERAPIA',
            'importe'    => $total_factura,
            'iva'        => 0,
            'username'   => session()->get('username')
        ];

        Factlin::create($faclin);

        Cita::whereIn('id',$citas)->update(['factura_id' => $f->id]);


    }

    private function desfacturar($data){

        $facturas = Factura::whereDate('fecha', '>=', $data['fecha_d'])
                            ->whereDate('fecha', '<=', $data['fecha_h'])
                            ->where('serie','A')
                            ->get();

        $facturas_id=array();
        foreach ($facturas as $factura){
            $facturas_id[] = $factura->id;
        }

        Factlin::whereIn('factura_id', $facturas_id)->delete();
        Cita::whereIn('factura_id', $facturas_id)->update(['factura_id' => null]);
        Factura::whereIn('id', $facturas_id)->delete();

    }
}
