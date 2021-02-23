<?php

namespace App\Http\Controllers\Mto;

use App\Models\Bono;
use App\Models\Cita;
use App\Models\Empresa;
use App\Models\Pacbono;
use App\Models\Tratamiento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mto\UpdatePacbonoRequest;

class PacbonosController extends Controller
{

    public function create(){

        if (request()->wantsJson())
            return [
                'bonos'     => Bono::selBonos()
            ];

    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'paciente_id'  => ['required', 'integer'],
            'bono_id'      => ['required', 'integer'],
            'fecha'        => ['required', 'date'],
        ]);

        $bono = Bono::findOrFail($data['bono_id']);


        $data['tratamiento_id'] = $bono->tratamiento_id;
        $data['importe'] = $bono->importe;
        $data['sesiones'] = $bono->sesiones;
        $data['caducidad']= $bono->caducidad;
        $data['bono'] = Empresa::incrementaBono();
        $data['username'] = session('username');

        $reg = Pacbono::create($data);

        if (request()->wantsJson())
            return ['pacbono'=>$reg, 'message' => 'EL registro ha sido creado'];
    }

    public function edit(Pacbono $pacbono)
    {

        $pacbono->load('paciente');

        if (request()->wantsJson())
            return [
                'pacbono'  => $pacbono,
                'tratamientos' => Tratamiento::selTratamientos(true),
               // 'bonos'     => Bono::selBonos()
            ];

    }

    public function update(UpdatePacbonoRequest $request, Pacbono $pacbono)
    {

        //$this->authorize('update', $paciente);

        $data = $request->validated();

        $data['username'] = session('username');

        $pacbono->update($data);

        $pacbono->load('paciente');

        if (request()->wantsJson())
            return ['pacbono'=>$pacbono, 'message' => 'EL registro ha sido modificado!'];

    }

    public function destroy(Pacbono $pacbono)
    {

        $c = Cita::where('bono', $pacbono->bono)
            ->where('estado_id','<>',4)
            ->get()
            ->count();

        if ($c > 0){
            return abort(422,'Hay citas asignadas a este bono, no se puede borrar!');
        }

        $pacbono->delete();

    }


}
