<?php

namespace App\Http\Controllers\Citas;

use App\Models\Hcita;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HCitasController extends Controller
{
    public function show($id){

        if (request()->wantsJson())
            return [
                'hcitas' => Hcita::with(['facultativo','tratamiento','estado','paciente'])->where('cita_id', $id)->get()
            ];

    }
}
