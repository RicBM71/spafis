<?php

namespace Database\Seeders;

use App\Models\Caja;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CajasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Caja::truncate();

        ini_set('memory_limit', '1024M');


        $reg = DB::connection('db2')->select('select * from cajasmov WHERE caja = 1');

        $i = 0;
        foreach($reg as $row){

            if ($row->simulado == 'C')
                $manual = 'C';
            elseif ($row->paciente > 0)
                $manual = "N";
            else
                $manual = "S";

            $data[]=array(
                'id'            => $row->id,
                'empresa_id'    => $row->caja,
                'fecha'         => $row->fecha,
                'nombre'        => $row->concepto,
                'importe'       => $row->importe,
                'dh'            => $row->dh,
                'paciente_id'   => $row->paciente,
                'manual'        => $manual,
                'created_at'    => $row->sysfum.' 00:00:00',
                'updated_at'    => $row->sysfum.' '.$row->syshum,
                'username'      => $row->sysusr
            );

            $i++;

            if ($i % 1000 == 0){
                DB::table('cajas')->insert($data);
                $data = array();
            }

        }

        DB::table('cajas')->insert($data);
    }
}
