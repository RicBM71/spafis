<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bono extends Model
{
    protected $fillable = [

        'nombre', 'importe', 'tratamiento_id', 'sesiones', 'caducidad', 'username',

    ];

    public static function selBonos()
    {

        return Bono::select('id AS value', 'nombre AS text')
            ->orderBy('nombre', 'asc')
            ->get();

    }

    public function tratamiento()
    {
    	return ($this->belongsTo(Tratamiento::class));
    }

}
