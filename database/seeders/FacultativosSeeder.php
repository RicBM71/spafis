<?php

namespace Database\Seeders;

use App\Models\Horario;
use App\Models\Facultativo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacultativosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Facultativo::truncate();
        Horario::truncate();

        $reg = DB::connection('db2')->select('select * from fisios');

        $data=array();

        foreach($reg as $row){


            $data[]=array(
                'id'        => $row->id,
                'nombre'    => $row->nombre,
                'apellidos' => $row->apellidos,
                'cif'       => $row->dni,
                'poblacion' => $row->poblacion,
                'direccion' => $row->direccion,
                'cpostal'   => $row->cpostal,
                'provincia' => $row->provincia,
                'telefono1' => $row->telefono1,
                'telefono2' => $row->telefono2,
                'telefonom' => $row->tfmovil,
                'email'     => $row->email,
                'fecha_nacimiento'=> $row->fnacimiento == '0000-00-00' ? null : $row->fnacimiento,
                'colegiado' => $row->colegiado,
                'fecha_baja'=> $row->fechabaja == '0000-00-00' ? null : $row->fechabaja,
                'iban'      => "XX00".$row->entidad.$row->oficina.$row->dc.$row->cuenta,
                'color'     => $row->color,
                'alias'     => $row->alias,
                'numero_ss' => $row->numsegso,
                'grupo_id'  => $row->grupo,
                'categoria_id' => 1,
                'orden'      => $row->orden,
                'created_at' => ($row->fechaalta != "0000-00-00" && $row->fechaalta != null) ? $row->fechaalta.' 00:00:00' : $row->sysfum.' '.$row->syshum,
                'updated_at' => $row->sysfum.' '.$row->syshum,
                'username'   => $row->sysusr
            );

        }

        DB::table('facultativos')->insert($data);

        $reg = DB::connection('db2')->select('select * from horarios');

        $data=array();

        foreach($reg as $row){


            $data[]=array(
                'id'         => $row->id,
                'facultativo_id'=> $row->fisio,
                'fecha'      => $row->fecha,
                'inim_1'     => $row->ini1_1,
                'finm_1'     => $row->fin1_1,
                'init_1'     => $row->ini2_1,
                'fint_1'     => $row->fin2_1,
                'inim_2'     => $row->ini1_2,
                'finm_2'     => $row->fin1_2,
                'init_2'     => $row->ini2_2,
                'fint_2'     => $row->fin2_2,
                'inim_3'     => $row->ini1_3,
                'finm_3'     => $row->fin1_3,
                'init_3'     => $row->ini2_3,
                'fint_3'     => $row->fin2_3,
                'inim_4'     => $row->ini1_4,
                'finm_4'     => $row->fin1_4,
                'init_4'     => $row->ini2_4,
                'fint_4'     => $row->fin2_4,
                'inim_5'     => $row->ini1_5,
                'finm_5'     => $row->fin1_5,
                'init_5'     => $row->ini2_5,
                'fint_5'     => $row->fin2_5,
                'inim_6'     => $row->ini1_6,
                'finm_6'     => $row->fin1_6,
                'init_6'     => $row->ini2_6,
                'fint_6'     => $row->fin2_6,
                'inim_0'     => $row->ini1_0,
                'finm_0'     => $row->fin1_0,
                'init_0'     => $row->ini2_0,
                'fint_0'     => $row->fin2_0,
                'created_at' => ($row->sysfum == "0000-00-00" ) ? null : $row->sysfum.' '.$row->syshum,
                'updated_at' => ($row->sysfum == "0000-00-00" ) ? null : $row->sysfum.' '.$row->syshum,
                'username'   => $row->sysusr
            );

        }

        DB::table('horarios')->insert($data);


    }
}
