<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TpvMov extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'empresa_id', 'fecha', 'paciente_id', 'tpv_id',  'tipo_pago', 'moneda', 'comercio', 'pedido', 'fecha_operacion',
        'titular_tarjeta', 'tarjeta', 'estado', 'codigo_respuesta','importe','autenticado', 'username'

    ];

}
