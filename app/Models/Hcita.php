<?php

namespace App\Models;

use App\Scopes\EmpresaActivaScope;
use Illuminate\Database\Eloquent\Model;

class Hcita extends Model
{
    protected $fillable = [
        'cita_id',
        'accion',
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
        'factura',
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

        static::addGlobalScope(new EmpresaActivaScope);

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
}
