<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historia extends Model
{
    protected $fillable = [

        'paciente_id',
        'fecha',
        'motivo',
        'exploracion',
        'juicio',
        'tratamiento',
        'diagnosticado',
        'notas',
        'informe',
        'cie',
        'facultativo_id',
        'username'

    ];

    // public function paciente(){
    //     return $this->hasOne(Paciente::class);
    // }

    public function paciente()
    {
    	return $this->belongsTo(Paciente::class);
    }



}
