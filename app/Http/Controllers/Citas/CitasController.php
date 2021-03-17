<?php

namespace App\Http\Controllers\Citas;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\Cita;
use App\Models\Mutua;
use App\Models\Adjunto;
use App\Models\Bloqueo;
use App\Models\Festivo;
use App\Models\Pacbono;
use App\Models\Historia;
use App\Models\Paciente;
use App\Models\Facultativo;
use App\Models\Tratamiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Citas\UpdateCitaRequest;

class CitasController extends Controller
{

    public function filtro(Request $request)
    {
        $parametros = $request->validate([
            'fecha'             => ['required','date'],
            'estado'            => ['required','max:1'],
            'facultativo_id'    => ['nullable','integer'],
        ]);

        if (session('facultativo_id') > 0)
            $parametros['facultativo_id'] = session('facultativo_id');

        $facultativos = Facultativo::selFacultativos();

        if (request()->wantsJson())
            return [
                'areas'         => Area::selAreas(),
                'citas'         => $this->getCitas(1, $parametros['fecha'], $parametros),
                'bloqueos'      => $this->getBloqueos($parametros['fecha']),
                'facultativos'  => $facultativos,
                'weekdays'      => $this->getSemana($parametros['fecha']),
                'today'         => Carbon::parse($parametros['fecha'])->format('Y-m-d'),
                'festivos'      => Festivo::whereYear('fecha', '>=', date('Y'))->get()->pluck('fecha'),
                'categories'    => $facultativos->pluck('text')
            ];
    }

    public function add(Request $request)
    {

        $data = $request->validate([
            'paciente_id'    => ['required','integer'],
            'fecha'          => ['required','date'],
            'facultativo_id' => ['nullable','integer']
        ]);

        //$this->authorize('create', new User);
        $paciente = Paciente::findOrFail($data['paciente_id']);

        $ult_cita = Cita::where('paciente_id', $data['paciente_id'])
                        ->where('estado_id', '<>', 4)
                        ->orderBy('fecha', 'desc')
                        ->first();

        if ($ult_cita == null){
            $ult_cita = false;
            $hora = false;
        }
        else{
            if ($data['facultativo_id'] == null)
                $data['facultativo_id'] = $ult_cita->facultativo_id;
            $hora = $ult_cita->hora;
        }

        $horas_disponibles = Cita::selectHorasFacultativo($data['fecha'], $data['facultativo_id'], $hora);

        if (request()->wantsJson())
            return[
                'paciente'     => $paciente,
                'tratamientos' => Tratamiento::selTratamientos(),
                'horas'        => $horas_disponibles,
                'facultativos' => Facultativo::selFacultativos(),
                'mutuas'       => Mutua::selMutuas(),
                'ult_cita'     => $ult_cita
            ];
        ;

    }

    private function huecos($fecha, $facultativo_id, $horas){

        $citas = Cita::where('fecha', $fecha)
                        ->where('area_id', 1)
                        ->where('facultativo_id', $facultativo_id)
                        ->where('estado_id', '<>', 4)
                        ->orderBy('hora', 'desc')
                        ->get();

        foreach ($citas as $cita){

            $hora = substr($cita->hora,0,5);


        }

    }

    public function store(UpdateCitaRequest $request)
    {
        //$this->authorize('update', $almacene);

        $data = $request->validated();

        if (strlen($data['fecha'] > 0))
            $data['fecha'] = substr($data['fecha'], 0, 10);

        $data['estado_id']=1;
        $data['empresa_id'] = session('empresa_id');
        $data['username'] = session('username');

        $cita = Cita::create($data);

        if (request()->wantsJson())
            return [
                'cita'     => $cita->load(['paciente.medio','tratamiento','facultativo','estado']),
                'saldo'    => Cita::getSaldo($cita->paciente_id, $cita->fecha, $cita->sociedad_id),
                'bono'     => Pacbono::getSesionesBono($cita->paciente_id, $cita->bono),
                'message'  => 'EL registro ha sido creado'
            ];
    }


    public function reload(Request $request){

        $data = $request->validate([
            'fecha'          => ['required', 'date'],
            'incremento'     => ['required', 'integer'],
            'estado'         => ['required', 'max:1'],
            'facultativo_id' => ['nullable', 'integer'],
        ]);

        if (session('facultativo_id') > 0){
            $data['facultativo_id'] = session('facultativo_id');
            $today = Carbon::today();
            $dif = $today->diffInDays(Carbon::parse($data['fecha']));

            if ($dif > 15){
                return abort(404,'Supera límite consulta');
            }
        }


        if ($data['incremento'] == 0){
            $desde = Carbon::parse($data['fecha']);
        }elseif ($data['incremento'] > 0){
            $desde = $this->sumarDias($data['fecha'], $data['incremento']);
        }else{
            $desde = $this->restarDias($data['fecha'], $data['incremento']);
        }


        $citas = $this->getCitas(1, $desde, $data);
        $bloqueos = $this->getBloqueos($desde);

        //$desde = $desde->toDateTimeString();
        $desde = $desde->format('Y-m-d');

        if (request()->wantsJson())
            return [
                'citas'     => $citas,
                'bloqueos'  => $bloqueos,
                'weekdays'  => $this->getSemana($desde),
                'today'     => $desde,
            ];
    }

    private function sumarDias($fecha, $dias){

        $fecha = Carbon::parse($fecha);

        $fecha->addDays($dias);

        if ($fecha->dayOfWeek == 0)
            $fecha->addDay();

        if ($fecha->dayOfWeek == 6)
            $fecha->addDays(2);

        return $fecha;

    }

    private function restarDias($fecha, $dias){

        $fecha = Carbon::parse($fecha)->subDays(abs($dias));

        if ($fecha->dayOfWeek == 0){
            $fecha->subDays(2);
            return $fecha;
        }

        if ($fecha->dayOfWeek == 6)
            $fecha->subDay();

        return $fecha;

    }

    /**
     *
     *
     *
     * @param [type] $area_id
     * @param [type] $desde
     * @param  array
     * @return void
     */
    private function getCitas($area_id, $desde, $parametros){

        $desde = Carbon::parse($desde);

        $hasta = Carbon::parse($desde);

        $desde = $desde->format('Y-m-d');
        $hasta = $hasta->addDays(10)->format('Y-m-d');


        $data = Cita::with(['paciente','facultativo','tratamiento'])
                        ->estado($parametros['estado'])
                        ->facultativo($parametros['facultativo_id'])
                        ->where('area_id', $area_id)
                        ->where('fecha', '>=',  $desde)
                        ->where('fecha', '<=',  $hasta)
                        ->orderBy('fecha')
                        ->orderBy('facultativo_id')
                        ->get();


        $colection = [];
        foreach ($data as $row){

            if ($row->facultativo->grupo_id > 1)
                continue;

            $dt = $row->fecha.' '.$row->hora;

            $start = Carbon::parse($dt)->toDateTimeString();

            // if ($row->tratamiento_id == 6 || $row->tratamiento_id == 7)
            //     $end = Carbon::parse($dt)->addMinutes(50)->toDateTimeString();
            // else
            $end = Carbon::parse($dt)->addMinutes($row->tratamiento->duracion_manual)->toDateTimeString();

            $colection[]=[
                'id'     => $row->id,
                'nomape' => $row->paciente->nomape,
                'start'  => $start,
                'end'    => $end,
                'color'  => $row->facultativo->color,
                'facultativo'=> $row->facultativo->nombre,
                'bono'   => $row->bono,
                'tratamiento'   => $row->tratamiento->nombre_reducido,
                'estado_id' => $row->estado_id,
                'paciente_id' => $row->paciente_id,
                'importe'   => $row->importe,
                'notas'     => $row->notas,
            ];
        }

        return $colection;

    }

    private function getBloqueos($desde){

        $desde = Carbon::parse($desde);
        $hasta = Carbon::parse($desde);

        $desde = $desde->format('Y-m-d');
        $hasta = $hasta->addDays(10)->format('Y-m-d');


        $data = Bloqueo::with(['facultativo'])
                        ->where('fecha', '>=',  $desde)
                        ->where('fecha', '<=',  $hasta)
                        ->orderBy('fecha')
                        ->orderBy('facultativo_id')
                        ->get();


        $colection = [];
        foreach ($data as $row){

            if ($row->facultativo->grupo_id > 1)
                continue;

            $start = $row->fecha.' '.$row->start;
            $end = $row->fecha.' '.$row->end;

            $colection[]=[
                'id'     => $row->id,
                'motivo' => $row->motivo,
                'start'  => $start,
                'end'    => $end,
                'color'  => 'grey',
                'facultativo'=> $row->facultativo->nombre,
            ];
        }

        return $colection;

    }

    private function getSemana($dt = null){

        $dia_semana = $dt === false ? Carbon::today()->weekday : Carbon::parse($dt)->dayOfWeek;

        for ($i = 1; $i <= 6; $i++){

            $weekdays[]=$dia_semana;
            $dia_semana++;

            if ($dia_semana > 5)
                $dia_semana = 1;

        }

        return $weekdays;
    }

    public function show(Cita $cita)
    {

        $fecha_saldo = Carbon::today();

        $adjuntos = Adjunto::where('paciente_id', $cita->paciente_id)
                            ->where('leido', false)
                            ->get()->count();


        if (request()->wantsJson())
            return [
                'cita'      => $cita->load(['paciente.medio','tratamiento','facultativo','estado']),
                'saldo'     => Cita::getSaldo($cita->paciente_id, $fecha_saldo, $cita->sociedad_id),
                'historia'  => Historia::where('paciente_id', $cita->paciente_id)->orderBy('fecha','desc')->first(),
                'bono'      => Pacbono::getSesionesBono($cita->paciente_id, $cita->bono),
                'whatsapp'  => getWhatsAppCita($cita),
                'ultima'    => Cita::getUltima($cita->paciente_id, $cita->fecha),
                'noleidos'  => $adjuntos,
            ];
        ;
    }

    public function edit(Cita $cita)
    {


        if (request()->wantsJson())
            return[
                'cita'         => $cita->load(['paciente.medio','tratamiento','facultativo','estado']),
                'tratamientos' => Tratamiento::selTratamientos(),
                'horas'        => Cita::selectHorasFacultativo($cita->fecha, $cita->facultativo_id, $cita->hora), //selectHoras(),
                'facultativos' => Facultativo::selFacultativos(),
                'mutuas'       => Mutua::selMutuas(),
            ];
        ;
    }

    public function update(UpdateCitaRequest $request, Cita $cita)
    {

        $data = $request->validated();

        $data['username'] = session('username');

        $cita->update($data);

        if (request()->wantsJson())
            return [
              //  'cita'     => $cita->load(['paciente.medio','tratamiento','facultativo','estado']),
              //  'saldo'    => Cita::getSaldo($cita->paciente_id, $cita->fecha, $cita->sociedad_id),
                'message'  => 'EL registro ha sido modificado'
            ];
    }

    /**
     * Cambia al estado indicado la cita dada
     *
     * @param Request $request
     * @param Cita $cita
     * @return void
     */
    public function estado(Request $request, Cita $cita)
    {

        $data = $request->validate([
            'estado_id' =>  ['required', 'integer'],
        ]);

        if ($data['estado_id'] == 4){
            $data['bono'] = null;
        }

        $data['username'] = session('username');

        $cita->update($data);

        if (request()->wantsJson())
            return [
                'cita'     => $cita->load(['paciente.medio','tratamiento','facultativo','estado']),
                'saldo'    => Cita::getSaldo($cita->paciente_id, $cita->fecha, $cita->sociedad_id),
                'message'  => 'EL registro ha sido modificado'
            ];
    }

    /**
     * Pone a tratado todas citas del día anteriores a la hora actal
     *
     * @param Request $request
     * @param Cita $cita
     * @return void
     */
    public function tratado(Request $request)
    {
        if (!esAdmin())
            return abort(411, 'No autorizado!');

        $hoy = Carbon::now();

        $data['estado_id'] = 2;
        $data['updated_at'] = $hoy;
        $data['username'] = session('username');

        $rows = Cita::where('area_id', 1)
                    ->where('fecha', $hoy->format('Y-m-d'))
                    ->where('hora', '<=', $hoy->format('H:m:s'))
                    ->where('estado_id', 1)
                    ->update($data);

        return response('Ok', 200);
    }



    public function ultimasCitas($paciente_id)
    {

        $data = Cita::with(['tratamiento','facultativo','estado'])
                    ->where('paciente_id',$paciente_id)
                    ->orderBy('fecha','desc')
                    ->get()
                    ->take(10);

        if (request()->wantsJson())
            return [
                'paciente' => Paciente::findOrFail($paciente_id),
                'citas'  => $data,
            ];
        ;
    }

    public function citasBono($numero_bono)
    {

        $bono = Pacbono::where('bono',$numero_bono)->first();

        $data = Cita::with(['tratamiento','facultativo','estado','paciente'])
                    ->where('bono', $numero_bono)
                    ->where('fecha','>=', $bono->fecha)
                    ->where('estado_id','<>',4)
                    ->orderBy('fecha','desc')
                    ->get();

        if (request()->wantsJson())
            return [
                'bono'   => $bono,
                'citas'  => $data,
            ];
        ;
    }

    public function reasignar(Request $request)
    {

        $data = $request->validate([
            'id'            =>  ['required', 'integer'],
            'paciente_id'   =>  ['required', 'integer'],
        ]);

        $data['username'] = session('username');

        Cita::where('id', $data['id'])->update($data);

        if (request()->wantsJson())
            return [
                // 'cita'     => $cita->load(['paciente.medio','tratamiento','facultativo','estado']),
                // 'saldo'    => Cita::getSaldo($cita->paciente_id, $cita->fecha, $cita->sociedad_id),
                'message'  => 'EL registro ha sido modificado'
            ];
    }

    public function destroy(Cita $cita)
    {

        if ($cita->estado_id != 4 && !esRoot())
            return abort(411, 'No autorizado!');

        $cita->delete();

        if (request()->wantsJson()){
            return response('OK', 200);
        }
    }

    public function addpro(UpdateCitaRequest $request)
    {

        $data = $request->validated();

        $data['empresa_id'] = session('empresa_id');
        $data['username'] = session('username');

        $cita = Cita::create($data);

        if (request()->wantsJson())
            return [
                'items' => Cita::with('tratamiento')
                            ->where('area_id',9999)
                            ->where('paciente_id', $data['paciente_id'])
                            ->where('estado_id', 1)
                            ->get()
            ];
    }


}
