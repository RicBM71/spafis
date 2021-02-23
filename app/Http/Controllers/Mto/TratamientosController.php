<?php

namespace App\Http\Controllers\Mto;

use App\Models\Iva;
use App\Models\Tratamiento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mto\UpdateTratamientoRequest;

class TratamientosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = Tratamiento::all();

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
        ]);

        $data['username'] = session('username');

        $reg = Tratamiento::create($data);

        if (request()->wantsJson())
            return ['registro'=>$reg, 'message' => 'EL registro ha sido creado'];
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Tratamiento $tratamiento)
    {

      //  $this->authorize('update', $user->load('empresas'));


        if (request()->wantsJson())
            return [
                'tratamiento'   => $tratamiento,
                'ivas'          => Iva::selIvas()
            ];

    }

    public function update(UpdateTratamientoRequest $request, Tratamiento $tratamiento)
    {

        //$this->authorize('update', $tratamiento);

        $data = $request->validated();

        $data['username'] = session('username');

        $tratamiento->update($data);

        if (request()->wantsJson())
            return ['registro'=>$tratamiento, 'message' => 'EL registro ha sido modificado!'];

    }

    public function destroy(Tratamiento $tratamiento)
    {

        //$this->authorize('delete', $tratamiento);

        $tratamiento->delete();

        if (request()->wantsJson()){
            return response()->json(Tratamiento::all());
        }


    }


}
