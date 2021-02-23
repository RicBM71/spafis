<?php

namespace App\Http\Controllers\Mto;

use App\Models\Bono;
use App\Models\Tratamiento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mto\UpdateBonoRequest;

class BonosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = Bono::all();

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
        //$this->authorize('create', new iva);

        if (request()->wantsJson())
            return [
                'tratamientos' => Tratamiento::selTratamientos()->get()
            ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:50'],
            'tratamiento_id' => ['required', 'integer'],
        ]);

        $data['username'] = session('username');

        $reg = Bono::create($data);

        if (request()->wantsJson())
            return ['registro'=>$reg, 'message' => 'EL registro ha sido creado'];
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Bono $bono)
    {

      //  $this->authorize('update', $user->load('empresas'));


        if (request()->wantsJson())
            return [
                'bono'         => $bono,
                'tratamientos' => Tratamiento::selTratamientos()->get()
            ];

    }

    public function update(UpdateBonoRequest $request, Bono $bono)
    {

        //$this->authorize('update', $bono);

        $data = $request->validated();

        $bono->update($data);

        if (request()->wantsJson())
            return ['registro'=>$bono, 'message' => 'EL registro ha sido modificado!'];

    }

    public function destroy(Bono $bono)
    {

        //$this->authorize('delete', $bono);

        $bono->delete();

        if (request()->wantsJson()){
            return response()->json(Bono::all());
        }


    }


}
