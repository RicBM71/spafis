<?php

namespace App\Http\Controllers\Mto;

use App\Models\Facultativo;
use App\Models\Historia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mto\UpdateHistoriaRequest;

class HistoriasController extends Controller
{

    public function create()
    {

        if (request()->wantsJson())
            return [
                'facultativos' => Facultativo::selFacultativos()
            ];

    }

    public function store(UpdateHistoriaRequest $request)
    {

        $data = $request->validated();

        //$data['fecha'] = Carbon::today();
        $data['username'] = session('username');

        $reg = Historia::create($data);

        if (request()->wantsJson())
            return ['historia'=>$reg, 'message' => 'EL registro ha sido creado'];
    }


    public function edit(Historia $historia)
    {

        activity()
            ->causedBy($historia)
            ->withProperties([
                'username' => session('username'),
                'historia' => $historia
            ])
            ->log('Edit');

        $historia->load('paciente');

        if (request()->wantsJson())
            return [
                'historia'  => $historia,
                'facultativos' => Facultativo::selFacultativos()
            ];

    }

    public function update(UpdateHistoriaRequest $request, Historia $historia)
    {

        //$this->authorize('update', $historia);

        $data = $request->validated();

        $data['username'] = session('username');

        $historia->update($data);

        if (request()->wantsJson())
            return ['historia'=>$historia, 'message' => 'EL registro ha sido modificado!'];

    }

    public function destroy(Historia $historia)
    {

        $historia->delete();

    }


}
