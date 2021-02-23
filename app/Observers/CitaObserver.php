<?php

namespace App\Observers;

use Carbon\Carbon;
use App\Models\Cita;
use App\Models\Hcita;

class CitaObserver
{


    public function created(Cita $cita)
    {


        $data = $cita->toArray();

        $data['cita_id'] = $cita->id;
        $data['id'] = null;
        $data['username'] = session('username');
        $data['created_at'] = Carbon::now();
        $data['accion'] = 'C';

        Hcita::create($data);

    }


    public function updating(Cita $cita)
    {

        $data = $cita->toArray();

        $data['cita_id'] = $cita->id;
        $data['id'] = null;
        $data['username'] = session('username');
        $data['created_at'] = Carbon::now();
        $data['accion'] = 'U';

        Hcita::create($data);

    }

     /**
     * Handle the Cita "deleted" event.
     *
     * @param  \App\Cita  $cita
     * @return void
     */
    public function deleted(Cita $cita)
    {


        $data = $cita->toArray();

        $data['cita_id'] = $cita->id;
        $data['id'] = null;
        $data['username'] = session('username');
        $data['created_at'] = Carbon::now();
        $data['accion'] = 'D';

        Hcita::create($data);

    }

}
