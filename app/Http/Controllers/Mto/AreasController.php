<?php

namespace App\Http\Controllers\Mto;


use App\Models\Area;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AreasController extends Controller
{

    public function index()
    {

        $data = Area::all();

        if (request()->wantsJson())
            return $data;
    }


    public function store(Request $request)
    {


        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:50'],
        ]);

        $data['username'] = $request->user()->username;

        $reg = Area::create($data);

        if (request()->wantsJson())
            return ['area'=>$reg, 'message' => 'EL registro ha sido creado'];
    }


    public function edit(Area $area)
    {

        if (request()->wantsJson())
            return [
                'area' =>$area
            ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Area $area)
    {

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:50'],
            'hora1' => ['required','date_format:H:i:s'],
            'hora2' => ['required','date_format:H:i:s'],
            'tarde' => ['required','date_format:H:i:s'],
            'frecuencia' => ['required', 'numeric', 'max:59'],
            'activo' => ['required', 'boolean'],
        ]);

        $data['username'] = $request->user()->username;


        $area->update($data);

        if (request()->wantsJson())
            return ['area'=>$area, 'message' => 'EL registro ha sido modificado'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Area $area)
    {

        $area->delete();

        if (request()->wantsJson()){
            return response()->json(Area::all());
        }
    }
}
