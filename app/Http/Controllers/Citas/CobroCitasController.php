<?php

namespace App\Http\Controllers\Citas;

use Carbon\Carbon;
use App\Models\Tpv;
use App\Models\Caja;
use App\Models\Cita;
use App\Models\Cobro;
use App\Models\Fpago;
use App\Models\TpvMov;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CobroCitasController extends Controller
{

    public function index(){

        if (request()->wantsJson())
            return [
                'fpagos' => Fpago::selFpagos()
            ];

    }

    public function load(Request $request)
    {
        //$this->authorize('update', $almacene);

        $data = $request->validate([
            'fecha'          => ['required', 'date'],
            'todo'           => ['required', 'boolean'],
            'paciente_id'    => ['required', 'integer'],
            'pacientes_id'   => ['required'],
            'mutua_id'       => ['nullable', 'integer']
        ]);

        $cobros = $this->formatCobros($this->getPendientesCobro($data), $data['paciente_id']);

        $saldo = $cobros->sum('importe');

        if (request()->wantsJson())
            return [
                'citas'  => $cobros,
                'saldo'  => number_format($saldo,2,'.',' '),
                'tpv'    => session('empresa')->tpv
            ];
    }

    public function submit(Request $request)
    {

        if (!hasCobros())
            return abort(405,'No tienes los permisos necesarios');

        $data = $request->validate([
            'fecha'          => ['required', 'date'],
            'todo'           => ['required', 'boolean'],
            'paciente_id'    => ['required', 'integer'],
            'pacientes_id'   => ['required'],
            'mutua_id'       => ['nullable', 'integer'],
            'importe'        => ['required', 'numeric'],
            'autorizacion'   => ['nullable', 'integer'],
            'fpago_id'       => ['required', 'integer']
        ]);

        $this->cobrar($this->getPendientesCobro($data), $data);

        $cobros = $this->formatCobros($this->getPendientesCobro($data), $data['paciente_id']);
        $saldo = $cobros->sum('importe');

        if (request()->wantsJson())
            return [
                'citas'  => $cobros,
                'saldo'  => number_format($saldo,2,'.',' ')
            ];
    }


    private function getPendientesCobro($data){

        $fecha = $data['fecha'];
        $mutua_id = $data['mutua_id'];

        $cobros = Cita::with(['paciente','tratamiento'])
                        ->whereIn('paciente_id', $data['pacientes_id'])
                        ->where('estado_id', '<=', 2)
                        ->where('importe', '<>', 0)
                        ->when($data['todo'] == false, function ($query) use ($fecha) {
                                return $query->where('fecha', '<=', $fecha);})
                        ->when($data['mutua_id'] > 0, function ($query) use ($mutua_id) {
                                return $query->where('mutua_id', $mutua_id);})
                        ->orderBy('fecha','asc')
                        ->get();

        return $cobros;

    }

    /**
     * Formate para vue
     *
     * @param array $cobros
     * @param integer $paciente_id el principal
     * @return collection
     */
    private function formatCobros($cobros, $paciente_id){

        $collection = array();

        foreach ($cobros as $row){

            $cobrado = Cobro::where('cita_id', $row->id)
                            ->sum('importe');
            $resto = round($row->importe - $cobrado,2);

            $collection[] =  [
                'cita_id'     => $row->id,
                'fecha'       => $row->fecha,
                'tratamiento' => $paciente_id != $row->paciente_id ? '++'.$row->tratamiento->nombre : $row->tratamiento->nombre,
                'importe'     => $resto

            ];

        }

        return collect($collection);
    }

    private function cobrar($cobros, $data){

        $saldo = $cobros->sum('importe');

        $importe_a_cobrar = $data['importe'];
        if ($importe_a_cobrar > $saldo){
            return abort(422,'Importe a cobrar supera a saldo pendiente');
        }

        $cobro_parcial = ($importe_a_cobrar < $saldo) ? true : false;

        $insert_efectivo = array();

        foreach ($cobros as $row){

            if ($importe_a_cobrar <= 0)
                break;

            $cobrado = Cobro::where('cita_id', $row->id)
                            ->sum('importe');
            $resto = round($row->importe - $cobrado,2);

            if ($importe_a_cobrar >= $row->importe){
                $row->update(['estado_id'=>3,'username'=>session('username')]);
            }

            if ($resto > $importe_a_cobrar)
                $resto = $importe_a_cobrar;
            else{
                if ($importe_a_cobrar >= $resto)
                    $row->update(['estado_id'=>3,'username'=>session('username')]);
            }

            $ahora = Carbon::now();

            $insert_cobros[]=[
                'cita_id'       => $row->id,
                'fecha'         => $data['fecha'],
                'paciente_id'   => $row->paciente_id,
                'importe'       => $resto,
                'fpago_id'      => $data['fpago_id'],
                'autorizacion'  => $data['autorizacion'],
                'empresa_id'    => $row->empresa_id,
                'area_id'       => $row->area_id,
                'username'      => session('username'),
                'updated_at'    => $ahora,
                'created_at'    => $ahora,
            ];

            if ($data['fpago_id'] == 1){
                $insert_efectivo[]= [
                    'empresa_id'    => session('empresa_id'),
                    'fecha'         => $data['fecha'],
                    'dh'            => 'H',
                    'importe'       => $resto,
                    'nombre'        => 'Cobro citas '.$row->paciente->nom_ape.' '.getFecha($row->fecha),
                    'cita_id'       => $row->cita,
                    'manual'        => 'N',
                    'username'      => session('username'),
                    'updated_at'    => $ahora,
                    'created_at'    => $ahora,
                ];
            }

            $importe_a_cobrar -= $resto;


        }


        DB::table('cobros')->insert($insert_cobros);

        DB::table('cajas')->insert($insert_efectivo);


    }

    public function cancelar(Request $request)
    {

        if (!hasCobros() && !esAdmin())
            return abort(405,'No tienes los permisos necesarios');

        $data = $request->validate([
            'fecha'          => ['required', 'date'],
            'paciente_id'    => ['required', 'integer'],
            'mutua_id'       => ['nullable', 'integer'],
            'fpago_id'       => ['required', 'integer'],
            'cita_id'        => ['required', 'integer']
        ]);

        $mutua_id = $data['mutua_id'];

        $cobros = Cobro::where('paciente_id', $data['paciente_id'])
                        ->whereDate('fecha', $data['fecha'])
                        ->where('fpago_id', $data['fpago_id'])
                        ->when($data['mutua_id'] > 0, function ($query) use ($mutua_id) {
                                return $query->where('mutua_id', $mutua_id);})
                        ->get();

        if ($cobros->count() == 0){
            return abort(404, 'No hay cobros para esa forma de pago!');
        }

        foreach ($cobros as $cobro){

            Cita::where('id', $cobro->cita_id)
                ->update([
                    'estado_id' => 2,
                    'username' => session('username'),
                    'updated_at' => date('Y-m-d h:i:s')
                    ]);

            $cobro->delete();

        }

        if ($data['fpago_id'] == 1){
            $kk = Caja::where('paciente_id', $data['paciente_id'])
                ->where('fecha', $data['fecha'])
                ->delete();
        }elseif($data['fpago_id'] == 2){
            TpvMov::where('paciente_id', $data['paciente_id'])
                    ->where('fecha', $data['fecha'])
                    ->delete();
        }

        if (request()->wantsJson())
            return [
               // 'cita' => Cita::with(['paciente.medio','tratamiento','facultativo','estado'])->findOrFail($data['cita_id'])
            ];
    }

    public function show($cita_id){

        if (request()->wantsJson())
            return [
               'cobros' => Cobro::with(['fpago'])->where('cita_id', $cita_id)->get()
            ];

    }

    public function recibo($paciente_id, $fecha){


        $recibo = Cobro::with('paciente')
                        ->where('paciente_id', $paciente_id)
                        ->whereDate('fecha', $fecha)
                        ->where('fpago_id', 1)
                        ->get();

        if ($recibo == null){
            return abort('404');
        }


        $paciente = $recibo->first()->paciente;
        $empresa  = session('empresa');
        $total = $recibo->sum('importe');

        return view('recibo', compact('empresa','fecha','total', 'paciente'));


    }
}
