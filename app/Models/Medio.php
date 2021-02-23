<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medio extends Model
{
    protected $fillable = [
        'nombre','username'
    ];

    /**
     *
     * @return Array formateado para select Vuetify
     *
     */
    public static function selMedios()
    {

        return Medio::select('id AS value', 'nombre AS text')
            ->orderBy('nombre', 'asc')
            ->get();

    }

    public function setNombreAttribute($nombre)
    {
        $this->attributes['nombre'] = strtoupper($nombre);

    }
}
