<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{

    protected $fillable = [

        'nombre','hora1','hora2','tarde','activo','frecuencia','username',

    ];

    public static function selAreas(){

        return Area::select('id AS value', 'nombre AS text')
            ->get();

    }

}
