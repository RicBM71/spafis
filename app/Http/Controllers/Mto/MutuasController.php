<?php

namespace App\Http\Controllers\Mto;

use App\Models\Mutua;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MutuasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = Mutua::all();

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
            'cif'    => ['nullable', 'string', 'max:15'],
        ]);

        $data['username'] = session('username');

        $reg = Mutua::create($data);

        if (request()->wantsJson())
            return ['mutua'=>$reg, 'message' => 'EL registro ha sido creado'];
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Mutua $mutua)
    {

        if (request()->wantsJson())
            return [
                'mutua'         => $mutua,
            ];

    }

    public function update(Request $request, Mutua $mutua)
    {

        $data = $request->validate([
            'nombre'    => ['required', 'string', 'max:50'],
            'cif'       => ['nullable', 'string', 'max:10'],
            'poblacion' => ['nullable', 'string', 'max:50'],
            'direccion' => ['nullable', 'string', 'max:50'],
            'cpostal'   => ['nullable', 'string', 'max:5'],
            'provincia' => ['nullable', 'string', 'max:50'],
            'telefono1' => ['nullable', 'string', 'max:15'],
            'telefono2' => ['nullable', 'string', 'max:15'],
            'email'     => ['nullable', 'string', 'max:15'],
            'contacto'  => ['nullable', 'string', 'max:50'],
        ]);

        $data['username']   = $request->user()->username;

        $mutua->update($data);

        if (request()->wantsJson())
            return ['mutua'=>$mutua, 'message' => 'EL registro ha sido modificado!'];

    }

    public function destroy(Mutua $mutua)
    {

        //$this->authorize('delete', $mutua);

        $mutua->delete();

        if (request()->wantsJson()){
            return response()->json(Mutua::all());
        }


    }

}
