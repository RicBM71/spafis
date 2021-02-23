<?php

namespace App\Models;

use Carbon\Carbon;
use App\Scopes\EmpresaActivaScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bloqueo extends Model
{

    protected $fillable = [
        'empresa_id', 'fecha', 'facultativo_id', 'start', 'end', 'motivo','remunerada','username'
    ];

    /**
     *
     * Añadimos global scope para filtrado por empresa.
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaActivaScope);
    }

    public function facultativo()
    {
    	return $this->belongsTo(Facultativo::class);
    }

    public function getStartAttribute($hora){

        return substr($hora,0,5);

    }

    public function getEndAttribute($hora){

        return substr($hora,0,5);

    }

    public static function getBloqueosFisio($fecha, $facultativo_id){

        $bloqueos = Bloqueo::whereDate('fecha', $fecha)
                            ->where('facultativo_id', $facultativo_id)
                            ->orderBy('start')
                            ->get();

        if ($bloqueos == null)
            return array();

        $data = array();
        foreach ($bloqueos as $row){

            $end = Carbon::parse($row->end)->subMinute()->format('H:i');

            $data[] = [
                'start' => $row->start,
                'end' => $end
            ];
        }

        return collect($data);
    }

}
