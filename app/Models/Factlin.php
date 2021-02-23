<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factlin extends Model
{
    protected $fillable  = [
        'factura_id',
        'concepto'  ,
        'iva'       ,
        'importe'   ,
        'username'  ,
    ];

    public static function totalFactura($factura_id){

        return Factlin::where('factura_id', $factura_id)->sum('importe');

    }
}
