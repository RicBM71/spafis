<?php

namespace Database\Seeders;

use App\Models\Bloqueo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BloqueosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '1024M');

        Bloqueo::truncate();

        $reg = DB::connection('db2')->select('select * from bloqueos ORDER by id');

        $i = 0;
        foreach($reg as $row){


            $data[]=array(

                'id' => $row->id,
                'empresa_id' => 1,
                'fecha' => $row->fecha,
                'facultativo_id' => $row->fisio,
                'start' => $row->inicio,
                'end' => $row->fin,
                'remunerada' => $row->remunerada == 'S'? true : false,
                'motivo' => $row->motivo,
                'created_at'    => $row->sysfum.' 00:00:00',
                'updated_at'    => $row->sysfum.' '.$row->syshum,
                'username'      => $row->sysusr
            );

            $i++;

            if ($i % 1000 == 0){
                DB::table('bloqueos')->insert($data);
                $data = array();
            }

        }

        DB::table('bloqueos')->insert($data);



    }
}
