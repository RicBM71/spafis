<?php

namespace Database\Seeders;

use App\Models\Bono;
use App\Models\Tratamiento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TratamientosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tratamiento::truncate();

        $reg = DB::connection('db2')->select('select * from tratamientos');

        foreach($reg as $row){


            $data[]=array(
                'id'        => $row->id,
                'nombre'    => $row->nombre,
                'nombre_web'      => $row->nomweb,
                'nombre_reducido' => $row->reducido,
                'importe'         => $row->importe,
                'importe_reducido'  => $row->imp_red,
                'precio_coste'      => $row->coste,
                'duracion_manual'   => 30,
                'duracion_aparatos' => 0,
                'edad'       => $row->edad,
                'tpv'        => $row->vendible == 'S' ? true : false,
                'inventario' => $row->inventario == 'N' ? false : true,
                'activo'     => $row->baja == 'N' ? true : false,
                'bono'       => $row->bono == 'S' ? true : false,
                'iva_id'     => $row->vendible == 'S' ? 1: 2,
                'created_at' => $row->sysfum.' 00:00:00',
                'updated_at' => $row->sysfum.' '.$row->syshum,
                'username'   => $row->sysusr
            );

        }

        DB::table('tratamientos')->insert($data);

        Bono::truncate();
        $reg = DB::connection('db2')->select('select * from bonos');

        $data=array();

        foreach($reg as $row){


            $data[]=array(
                'id'        => $row->id,
                'nombre'    => $row->nombre,
                'importe'        => $row->importe,
                'tratamiento_id' => $row->tratamiento,
                'sesiones'   => $row->sesiones,
                'caducidad'  => $row->caducidad,
                'created_at' => $row->sysfum.' 00:00:00',
                'updated_at' => $row->sysfum.' '.$row->syshum,
                'username'   => $row->sysusr
            );

        }

        DB::table('bonos')->insert($data);

    }
}
