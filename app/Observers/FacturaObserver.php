<?php

namespace App\Observers;

use Carbon\Carbon;
use App\Models\Cita;
use App\Models\Factura;

class FacturaObserver
{


    public function deleted(Factura $factura)
    {

        $data = [
            'factura_id' => null,
            'updated_at' => Carbon::now(),
            'username'   => session('username')
        ];

        Cita::where('factura_id', $factura->id)
                ->update($data);

    }


}
