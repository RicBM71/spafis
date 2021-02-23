<?php

namespace App\Imports;

use App\Banco;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class BancosImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        return new Banco([
            'entidad'   => $row[0],
            'nombre'    => $row[1],
            'bic'       => $row[2]  ,
        ]);
    }
}
