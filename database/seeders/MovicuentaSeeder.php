<?php

namespace Database\Seeders;

use App\Models\Grupo;
use App\Models\Partida;
use App\Models\Movicuenta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovicuentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Movicuenta::truncate();
        Partida::truncate();
        Grupo::truncate();

        $reg = DB::connection('db2')->select('select * from movicuen ORDER by id');

        $i = 0;
        foreach($reg as $row){

            $data[]=array(
                'id'            => $row->id,
                'empresa_id'    => 1,
                'cuenta_id'     => $row->idcuen,
                'fecha'         => $row->fecha,
                'dh'            => $row->dh,
                'concepto'      => $row->concepto,
                'importe'       => $row->importe,
                'partida_id'    => $row->partida <= 0 ? null : $row->partida,
                'validado'      => true,
                'created_at'    => $row->sysfum.' 00:00:00',
                'updated_at'    => $row->sysfum.' '.$row->syshum,
                'username'      => $row->sysusr
            );

            $i++;

            if ($i % 1000 == 0){
                DB::table('movicuentas')->insert($data);
                $data = array();
            }

        }

        DB::table('movicuentas')->insert($data);

        // GRUPOS

        $data=array();
        $reg = DB::connection('db2')->select('select * from grupos ORDER by id');
        foreach($reg as $row){

            $data[]=array(
                'id'            => $row->id,
                'nombre'        => $row->nombre,
                'created_at'    => $row->sysfum.' 00:00:00',
                'updated_at'    => $row->sysfum.' '.$row->syshum,
            );
        }
        DB::table('grupos')->insert($data);

        // PARTIDAS

        $data=array();
        $reg = DB::connection('db2')->select('select * from partidas ORDER by id');
        foreach($reg as $row){

            $data[]=array(
                'id'            => $row->id,
                'nombre'        => $row->nombre,
                'grupo_id'      => $row->grupo,
                'patron'        => $row->patron,
                'created_at'    => $row->sysfum.' 00:00:00',
                'updated_at'    => $row->sysfum.' '.$row->syshum,
            );
        }
        DB::table('partidas')->insert($data);


    }
}
