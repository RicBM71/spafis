<?php

namespace App\Http\Controllers\Mto;

use App\Models\Horario;
use App\Models\Facultativo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mto\UpdateHorarioRequest;

class HorariosController extends Controller
{

    public function index()
    {

        $data = Horario::where('facultativo_id',session('facultativo_id'))->get();

        if (request()->wantsJson())
            return [
                'horario' => $data,
                'facultativo'=> Facultativo::findOrFail(session('facultativo_id'))
            ];
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'fecha' => ['required', 'date'],
        ]);

        $data['username'] = session('username');
        $data['facultativo_id']=session('facultativo_id');

        $reg = Horario::create($data);

        if (request()->wantsJson())
            return ['registro'=>$reg, 'message' => 'EL registro ha sido creado'];
    }


    public function edit(Horario $horario)
    {

        $horario->load('facultativo');

        if (request()->wantsJson())
            return [
                'registro'     => $horario,
            ];

    }

    public function update(UpdateHorarioRequest $request, Horario $horario)
    {

        //$this->authorize('update', $facultativo);

        $data = $request->validated();

        $data['username'] = session('username');

        $horario->update($data);

        if (request()->wantsJson())
            return ['registro'=>$horario, 'message' => 'EL registro ha sido modificado!'];

    }

    public function destroy(Horario $horario)
    {

        //$this->authorize('delete', $facultativo);


        $horario->delete();

        if (request()->wantsJson())
            return Horario::where('facultativo_id',session('facultativo_id'))->get();


    }



}
