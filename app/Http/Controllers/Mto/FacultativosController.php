<?php

namespace App\Http\Controllers\Mto;

use App\Models\Categoria;
use Carbon\Carbon;
use App\Models\Facultativo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mto\UpdateFacultativoRequest;

class FacultativosController extends Controller
{



        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = Facultativo::with(['categoria'])->activos(true)->get();

        if (request()->wantsJson())
            return $data;
    }

    public function filtrar(Request $request)
    {

        $data = $request->validate([
            'activo' => ['boolean'],
        ]);


        if (request()->wantsJson()){
            return $this->seleccionar($data);
        }

    }

    /**
     *  @param array $data // condiciones where genéricas
     *  @param array $doc  // condiciones para documentos
     */
    private function seleccionar($data){


        return Facultativo::with(['categoria'])->activos($data['activo'])->get();


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
                'categorias' => Categoria::selCategorias()
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
            'apellidos' => ['required', 'string', 'max:50'],
            'categoria_id' => ['required', 'integer'],
        ]);

        $data['username'] = session('username');

        $reg = Facultativo::create($data);

        if (request()->wantsJson())
            return ['registro'=>$reg, 'message' => 'EL registro ha sido creado'];
    }


    public function show(Facultativo $facultativo)
    {

        $facultativo->load('categoria');

        if (request()->wantsJson())
            return [
                'registro'     => $facultativo,
            ];

    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Facultativo $facultativo)
    {
        if ($facultativo->fecha_baja != null)
            $this->show($facultativo->id);
      //  $this->authorize('update', $user->load('empresas'));


        session(['facultativo_id' => $facultativo->id]);

        if (request()->wantsJson())
            return [
                'registro'     => $facultativo,
                'categorias' => Categoria::selCategorias()
            ];

    }

    public function update(UpdateFacultativoRequest $request, Facultativo $facultativo)
    {

        //$this->authorize('update', $facultativo);

        $data = $request->validated();

        $facultativo->update($data);

        if (request()->wantsJson())
            return ['registro'=>$facultativo, 'message' => 'EL registro ha sido modificado!'];

    }

    public function destroy(Facultativo $facultativo)
    {

        //$this->authorize('delete', $facultativo);

        $data = [
            'fecha_baja' => Carbon::today()
        ];

        $facultativo->update($data);

        if (request()->wantsJson()){
            return response()->json(Facultativo::with(['categoria'])->activos(true)->get());
        }


    }


}
