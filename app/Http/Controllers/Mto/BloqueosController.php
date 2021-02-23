<?php

namespace App\Http\Controllers\Mto;

use App\Models\Bloqueo;
use App\Models\Festivo;
use App\Models\Facultativo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BloqueosController extends Controller
{
    public function index()
    {

        $data = Bloqueo::with('facultativo')->get();

        if (request()->wantsJson())
            return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$this->authorize('create', new Festivo);

        if (request()->wantsJson())
            return ['facultativos' => Facultativo::selFacultativos()];

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

     //   $this->authorize('create', new Festivo);

        $data = $request->validate([
            'fecha'          => ['required', 'date'],
            'facultativo_id' => ['required', 'integer'],
        ]);

        $data['empresa_id'] =  session()->get('empresa')->id;
        $data['username'] = $request->user()->username;
        $data['start'] = "09:00:00";
        $data['end'] = "20:00:00";

        $reg = Bloqueo::create($data);

        if (request()->wantsJson())
            return ['reg'=>$reg, 'message' => 'EL registro ha sido creado'];
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Bloqueo $bloqueo)
    {
       // $this->authorize('update', $bloqueo);

        if (request()->wantsJson())
            return [
                'facultativos' => Facultativo::selFacultativos(),
                'reg' =>$bloqueo
            ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bloqueo $bloqueo)
    {
       // $this->authorize('update', $bloqueo);

        $data = $request->validate([
            'fecha'          => ['required', 'date'],
            'facultativo_id' => ['required', 'integer'],
            'motivo'         => ['nullable', 'max:100'],
            'start'         => ['required' ],
            'end'           => ['required'],
        ]);

        $data['username'] = $request->user()->username;


        $bloqueo->update($data);

        if (request()->wantsJson())
            return ['reg'=>$bloqueo, 'message' => 'EL registro ha sido modificado'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bloqueo $bloqueo)
    {
       // $this->authorize('delete', $bloqueo);

        $bloqueo->delete();

        if (request()->wantsJson()){
            return response()->json(Bloqueo::all());
        }
    }
}
