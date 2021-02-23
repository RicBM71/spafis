<?php

namespace App\Http\Controllers\Mto;

use App\Models\Festivo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FestivosController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = Festivo::all();

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
            'fecha' => ['required', 'date','unique:festivos'],
        ]);

        $data['username'] = $request->user()->username;

        $reg = Festivo::create($data);

        if (request()->wantsJson())
            return ['festivo'=>$reg, 'message' => 'EL registro ha sido creado'];
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Festivo $festivo)
    {
       // $this->authorize('update', $festivo);

        if (request()->wantsJson())
            return [
                'festivo' =>$festivo
            ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Festivo $festivo)
    {
       // $this->authorize('update', $festivo);

        $data = $request->validate([
            'fecha' => ['required', 'date'],
        ]);

        $data['username'] = $request->user()->username;


        $festivo->update($data);

        if (request()->wantsJson())
            return ['festivo'=>$festivo, 'message' => 'EL registro ha sido modificado'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Festivo $festivo)
    {
       // $this->authorize('delete', $festivo);

        $festivo->delete();

        if (request()->wantsJson()){
            return response()->json(Festivo::all());
        }
    }
}
