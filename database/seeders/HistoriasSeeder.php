<?php

namespace Database\Seeders;

use App\Models\Historia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HistoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Historia::truncate();

        $reg = DB::connection('db2')->select('select * from pachist ORDER by id');

        $i = 0;
        foreach($reg as $row){


            if ($row->fecha == '0200-12-20')
                $row->fecha = "2000-12-20";

            $data[]=array(

                'paciente_id'   => $row->paciente,
                'fecha'         => $row->fecha,
                'motivo'        => $row->motivo=='' ? null : $row->motivo,
                'exploracion'   => $row->exploracion=='' ? null : $row->exploracion,
                'juicio'        => $row->juicio=='' ? null : $row->juicio,
                'tratamiento'   => $row->tratamiento=='' ? null : $row->tratamiento,
                'diagnosticado' => $row->diagnosticado == 'S' ? true : false,
                'informe'       => $row->interno== 'S' ? false : true,
                'cie'           => $row->cie,
                'facultativo_id'   => $row->fisio > 0 ? $row->fisio : null,
                'created_at'    => $row->fecha.' 00:00:00',
                'updated_at'    => $row->sysfum.' '.$row->syshum,
                'username'      => $row->sysusr
            );

            $i++;

            if ($i % 1000 == 0){
                DB::table('historias')->insert($data);
                $data = array();
            }

        }

        DB::table('historias')->insert($data);

    }
}
