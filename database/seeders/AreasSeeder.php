<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class AreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Area::truncate();

        $reg = DB::connection('db2')->select('select * from centros');

        foreach($reg as $row){

            $data[]=array(
                'id'        => $row->id,
                'nombre'    => $row->nombre,
                'hora1'       => $row->hora1,
                'hora2' => $row->hora2,
                'tarde' => $row->tarde,
                'activo'   => $row->activo == 'S' ? true : false,
                'frecuencia' => $row->frecuencia,
                'created_at' => $row->sysfum.' '.$row->syshum,
                'updated_at' => $row->sysfum.' '.$row->syshum,
                'username'   => $row->sysusr
            );

        }

        DB::table('areas')->insert($data);
    }
}
