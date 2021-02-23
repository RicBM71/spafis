<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    protected $fillable = [

        'nombre', 'nombre_web', 'nombre_reducido', 'importe', 'importe_reducido', 'precio_coste', 'duracion_manual',
        'duracion_aparatos', 'edad', 'tpv', 'inventario', 'activo', 'username','bono','iva_id'

    ];

    public function iva()
    {
        return $this->belongsTo(Iva::class);
    }


    public static function selTratamientos($con_bono = null){

        return Tratamiento::select('id AS value', 'nombre AS text')
            ->where('activo', true)
            ->where('tpv', false)
            ->where('inventario', false)
            ->bono($con_bono)
            ->orderBy('nombre', 'asc')
            ->get();


    }

    public static function selVendibles(){

        return Tratamiento::select('tratamientos.id AS value', 'tratamientos.nombre AS text','tratamientos.importe','ivas.importe AS iva')
                ->join('ivas','ivas.id','iva_id')
                ->where('activo', true)
                ->where('tpv', true)
                ->orderBy('tratamientos.nombre', 'asc')
                ->get();


    }

    public function scopeBono($query, $con_bono){

        if ($con_bono != null)
            $query->where('bono', $con_bono);

        return $query;

    }


}
