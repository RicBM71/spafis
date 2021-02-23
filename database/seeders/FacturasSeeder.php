<?php

namespace Database\Seeders;

use App\Models\Factlin;
use App\Models\Factura;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacturasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Factura::truncate();
        Factlin::truncate();

        $reg = DB::connection('db2')->select('select * from facturas ORDER by id');

        $i = 0;
        foreach($reg as $row){

            $data[]=array(
                'id'            => $row->id,
                'empresa_id'    => $row->empresa,
                'ejercicio'     => $row->ejercicio,
                'factura'       => $row->factura,
                'serie'         => $row->serie,
                'fecha'         => $row->fecha,
                'paciente_id'   => $row->paciente,
                'mutua_id'      => $row->sociedad <= 0 ? null : $row->sociedad,
                'razon'         => $row->razon,
                'direccion'     => $row->direccion,
                'poblacion'     => $row->poblacion,
                'cpostal'       => $row->cpostal == '' ? null : $row->cpostal,
                'provincia'     => $row->provincia,
                'cif'           => $row->cif == '' ? null : $row->cif,
                'cuenta_id'     => null,
                'fpago_id'      => $row->fpago,
                'notas'         => $row->notas,
                'created_at'    => $row->sysfum.' 00:00:00',
                'updated_at'    => $row->sysfum.' '.$row->syshum,
                'username'      => $row->sysusr
            );

            $i++;

            if ($i % 1000 == 0){
                DB::table('facturas')->insert($data);
                $data = array();
            }

        }

        DB::table('facturas')->insert($data);

        $data=array();

        $reg = DB::connection('db2')->select('select * from faclin ORDER by id');

        $i = 0;
        foreach($reg as $row){

            if ($row->sysfum == "0000-00-00")
                $row->sysfum = "2010-01-01";

            $data[]=array(
                'id'            => $row->id,
                'factura_id'    => $row->id_fact,
                'concepto'      => $row->texto,
                'iva'           => $row->iva,
                'importe'       => $row->importe,
                'created_at'    => $row->sysfum.' 00:00:00',
                'updated_at'    => $row->sysfum.' '.$row->syshum,
                'username'      => $row->sysusr
            );

            $i++;

            if ($i % 1000 == 0){
                DB::table('factlins')->insert($data);
                $data = array();
            }

        }

        DB::table('factlins')->insert($data);

    }
}
