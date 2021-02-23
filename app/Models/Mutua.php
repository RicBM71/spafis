<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mutua extends Model
{

    protected $fillable = [

        'nombre'    ,
        'cif'       ,
        'poblacion' ,
        'direccion' ,
        'cpostal'   ,
        'provincia' ,
        'telefono1' ,
        'telefono2' ,
        'email'     ,
        'contacto'  ,
        'username'  ,

    ];

    public static function selMutuas(){

        return Mutua::select('id AS value', 'nombre AS text')
            ->orderBy('nombre', 'asc')
            ->get();

    }

}
