<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    protected $fillable = [
        'empresa_id','nombre', 'username'
    ];


    public static function selAlmacenes(){

        return Almacen::select('id AS value', 'nombre AS text')
            ->orderBy('nombre', 'asc')
            ->get();

    }


}
