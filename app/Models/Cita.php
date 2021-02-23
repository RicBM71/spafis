<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Bono;
use App\Models\Cobro;
use App\Models\Estado;
use App\Models\Bloqueo;
use App\Models\Paciente;
use App\Scopes\CitaScope;
use App\Models\Facultativo;
use App\Models\Tratamiento;
use App\Observers\CitaObserver;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = [
        'id',
        'empresa_id',
        'area_id',
        'paciente_id',
        'fecha',
        'hora',
        'facultativo_id',
        'estado_id',
        'tratamiento_id',
        'importe',
        'importe_ponderado',
        'iva',
        'bono',
        'apunte',
        'fecha_cobro',
        'factura_id',
        'ejercicio',
        'sesiones',
        'mutua_id',
        'autorizacion',
        'tipo_envio',
        'envio_sms',
        'sms_id',
        'notas',
        'tune',
        'username',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CitaScope);

        Cita::observe(CitaObserver::class);

    }

    public function getHoraAttribute($hora){

        return \substr($hora,0,5);

    }

    public function paciente()
    {
    	return $this->belongsTo(Paciente::class);
    }

    public function tratamiento()
    {
    	return $this->belongsTo(Tratamiento::class);
    }

    public function facultativo()
    {
    	return $this->belongsTo(Facultativo::class);
    }

    public function mutua()
    {
    	return $this->belongsTo(Tratamiento::class);
    }

    public function estado()
    {
    	return $this->belongsTo(Estado::class);
    }

    public function cobros()
    {
        return $this->hasMany(Cobro::class);
    }

    public function getUsadasBono($bono){

        $bono = Bono::where('bono', $bono)->where('estado_id','<>', 4)->count();

    }

    public function scopeEstado($query, $estado){

        if ($estado == 'B')
            return $query->where('estado_id','=', 4);
        elseif ($estado == 'A')
            return $query->where('estado_id','<>', 4);
        else
            return $query;

    }

    public function scopeFacultativo($query, $facultativo_id){

        if ($facultativo_id != null)
            return $query->where('facultativo_id', $facultativo_id);
        else
            return $query;

    }


    public static function getSaldo($paciente_id, $fecha, $mutua_id = 0){


        $data = DB::table('citas')
                    ->select(DB::raw('IFNULL(SUM('.DB::getTablePrefix().'citas.importe), 0) AS importe, IFNULL(SUM('.DB::getTablePrefix().'cobros.importe), 0) AS cobrado'))
                    ->leftJoin('cobros', 'citas.id', '=', 'cita_id')
                    ->where('citas.paciente_id', $paciente_id)
                    ->where('citas.fecha', '<=', $fecha)
                    ->where('citas.estado_id', '<=', '2')
                    ->when($mutua_id > 0, function ($query) use ($mutua_id) {
                        return $query->where('mutua_id', $mutua_id);
                    })->get()->first();

        return ($data == null) ? 0 : $data->importe - $data->cobrado;


    }

    public static function getUltima($paciente_id, $fecha){

        $cita = Cita::where('paciente_id', $paciente_id)
            ->where('fecha', '<', $fecha)
            ->where('estado_id', '<>', 4)
            ->orderBy('fecha','desc')
            ->first();

        if ($cita == null)
            return '';

        $dt = Carbon::today();

        $f = Carbon::parse($cita->fecha);

        return '(+'.$dt->diffInDays($f).' días)';

    }

    public static function selectHorasFacultativo($fecha, $facultativo_id, $hora_actual = false, $frecuencia = 30){

        if ($facultativo_id == null){
            return ['text'=> '09:00', 'value'=>'09:00'];
        }

        $dt = Carbon::create(2000, 1, 1, 9, 0, 0);

        $limite = Carbon::create(2000, 1, 1, 20, 0, 0);
        $limite->subMinutes($frecuencia);

        $ocupadas = Cita::select('hora')
                            ->whereDate('fecha', $fecha)
                            ->where('facultativo_id', $facultativo_id)
                            ->where('estado_id','<>',4)
                            ->orderBy('hora','asc')
                            ->get()
                            ->pluck('hora');

        $horas=array();
        $fecha = Carbon::parse($fecha);

        $bloqueos = Bloqueo::getBloqueosFisio($fecha, $facultativo_id);

        $horario = Horario::getHorario($fecha, $facultativo_id);

        $f = $fecha->format('Y-m-d');


        while ($dt->toTimeString() <= $limite){

            $h = $dt->format('H:i');
            $hl = $dt->format('H:i:s');

            // no está dentro del horario
            if (!(Carbon::parse($hl)->betweenIncluded($horario['start_m'], $horario['end_m']) || Carbon::parse($hl)->betweenIncluded($horario['start_t'], $horario['end_t']))){
                $dt->addMinutes($frecuencia);
                continue;
            }

            // lo hago así por si hay más de un bloqueo en el día.
            foreach ($bloqueos as $bloqueo){

                if (Carbon::parse($hl)->betweenIncluded($bloqueo['start'], $bloqueo['end'])){
                    $dt->addMinutes($frecuencia);
                    continue 2;
                }
            }


            $v = $ocupadas->contains($h);
            if ($hora_actual == $h)
                 ;
            elseif($ocupadas->contains($h)){
                $dt->addMinutes($frecuencia);
                continue;
            }
            if ($fecha->dayOfWeek != 5)
                if ($h >= "14:30" && $h <= "16:00"){
                    $dt->addMinutes($frecuencia);
                    continue;
                }
            else{
                if ($h >= "14:00" && $h <= "16:00"){
                    $dt->addMinutes($frecuencia);
                    continue;
                }
            }

            $horas[]=['text'=> $h, 'value'=>$h];

            $dt->addMinutes($frecuencia);
        }

        return $horas;
    }
}
