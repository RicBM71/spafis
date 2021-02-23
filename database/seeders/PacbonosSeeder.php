<?php

namespace Database\Seeders;

use App\Models\Pacbono;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PacbonosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pacbono::truncate();

        $reg = DB::connection('db2')->select('select * from pacbono ORDER by id');

        $i = 0;
        foreach($reg as $row){

            if ($row->sysfum == "0000-00-00")
                $row->sysfum = "2010-01-01";

            $data[]=array(
                'id'     => $row->id,
                'paciente_id' => $row->paciente,
                'bono' => $row->bono,
                'fecha' => $row->fecha,
                'sesiones' => $row->sesiones,
                'tratamiento_id' => $row->tratamiento,
                'importe' => $row->importe,
                'caducidad' => $row->caducidad,
                'caducado' => $row->caducado == 'S' ? true : false,
                'texto' => $row->texto,
                'created_at'    => $row->sysfum.' 00:00:00',
                'updated_at'    => $row->sysfum.' '.$row->syshum,
                'username'      => $row->sysusr
            );

            $i++;

            if ($i % 1000 == 0){
                DB::table('pacbonos')->insert($data);
                $data = array();
            }

        }

        DB::table('pacbonos')->insert($data);
    }
}
