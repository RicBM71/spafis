<?php

namespace Database\Seeders;

use App\Banco;
use Illuminate\Http\File;
use Maatwebsite\Excel\Excel;
use App\Imports\BancosImport;
use Illuminate\Database\Seeder;

class BancosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Banco::truncate();

        Excel::import(new BancosImport, 'database/data/bancos.xlsx');



        // $json = File::get("database/data/bancos.csv");
        // $data = json_decode($json);

        // foreach ($data as $obj) {
        //     Banco::create(array(
        //      'id' => $obj->id,
        //      'nombre' => $obj->nombre,
        //      'color' => $obj->color,
        //      'created_at'=> $obj->created_at,
        //      'updated_at'=> $obj->updated_at
        //    ));
        // }



    }
}
