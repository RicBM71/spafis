<?php

namespace Database\Seeders;

use App\Models\Mutua;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MutuasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Mutua::truncate();

        $reg = DB::connection('db2')->select('select * from sociedades');

        foreach($reg as $row){


            $data[]=array(
                'id'        => $row->id,
                'nombre'    => $row->nombre,
                'cif'       => $row->cif,
                'poblacion' => $row->poblacion,
                'direccion' => $row->direccion,
                'cpostal'   => $row->cpostal,
                'provincia' => $row->provincia,
                'telefono1' => $row->tf1,
                'telefono2' => $row->tf2,
                'email'     => $row->email,
                'contacto'  => $row->contacto,
                'created_at' => ($row->sysfum != "0000-00-00" && $row->sysfum != null) ? $row->sysfum.' 00:00:00' : $row->sysfum.' '.$row->syshum,
                'updated_at' => ($row->sysfum != "0000-00-00" && $row->sysfum != null) ? $row->sysfum.' 00:00:00' : $row->sysfum.' '.$row->syshum,
                'username'   => $row->sysusr
            );

        }

        DB::table('mutuas')->insert($data);

    }
}
