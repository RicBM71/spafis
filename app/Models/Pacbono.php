<?php

namespace App\Models;

use App\Models\Bono;
use App\Models\Pacbono;
use App\Models\Paciente;
use App\Models\Tratamiento;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Pacbono extends Model
{
    protected $fillable = [

        'paciente_id',
        'bono',
        'fecha',
        'sesiones',
        'tratamiento_id',
        'importe',
        'caducidad',
        'caducado',
        'texto',
        'username',

    ];

    public static function selBonos(){

        return Bono::select('id AS value', 'nombre AS text')
            ->orderBy('nombre', 'asc')
            ->get();


    }


    public function paciente()
    {
    	return $this->belongsTo(Paciente::class);
    }

    public function tratamiento()
    {
    	return $this->belongsTo(Tratamiento::class);
    }

    public static function getSesionesBono($paciente_id, $numero_bono){


        $data = [
            'numero_bono' => 0,
            'sesiones' => 0,
            'resto'    => 0];

        if ($numero_bono == null || $numero_bono == 0)
            return $data;

        $bono = Pacbono::where('paciente_id', $paciente_id)
                        ->where('bono', $numero_bono)->get()->first();



        if ($bono == null) return $data;



        $resto = DB::table('citas')
                    // ->where('citas.paciente_id', $paciente_id)
                    ->where('citas.bono', $numero_bono)
                    ->where('citas.fecha', '>=', $bono->fecha)
                    ->where('citas.estado_id', '<>', '4')
                    ->count();

        // if ($resto == null)
        //     return $data;

        return [
            'numero_bono' => $bono->bono,
            'sesiones'    => $bono->sesiones,
            'resto'       => $resto
        ];

	}

}
