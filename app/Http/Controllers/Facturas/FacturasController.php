<?php

namespace App\Http\Controllers\Facturas;

use Carbon\Carbon;
use App\Models\Fpago;
use App\Models\Cuenta;
use App\Models\Factura;
use App\Models\Paciente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Facturas\UpdateFacturaRequest;

class FacturasController extends Controller
{
    public function index(){


        if (request()->session()->has('filtro_ven')){
            $data = $this->miFiltro();
        }else{
            $data = Factura::with(['paciente','factlins','cuenta','fpago'])
                        ->whereYear('fecha','=',date('Y'))
                        //->whereYear('fecha','=',2020)
                        ->get();
        }

        if (request()->wantsJson())
            return $data;
    }

    public function filtrar(FiltroAlbRequest $request)
    {

        $data = $request->validated();

        session(['filtro_ven' => $data]);

        return $this->miFiltro();

    }

    private function miFiltro(){

        $data = request()->session()->get('filtro_ven');

        return Factura::with(['paciente','factlins','cuenta','fpago'])
                        ->fecha($data['fecha_d'],$data['fecha_h'],$data['quefecha'])
                        ->fpago($data['fpago_id'])
                        ->get();

    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $this->authorize('create', Factura::class);

        // if (!auth()->user()->hasPermissionTo('addcom')){
        //     return abort(422,auth()->user()->name.' NO tiene permiso para crear compras');
        // }

        if (request()->wantsJson())
            return [
                'cuentas'  => Cuenta::selCuentas(),
                'fpagos'  => Fpago::selFpagos(),
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
            'fecha' => ['required','date'],
            'paciente_id' => ['required','integer'],
        ]);

        $paciente = Paciente::findOrFail($data['paciente_id']);

        $data['razon']     = $paciente->nom_ape;
        $data['direccion'] = $paciente->direccion;
        $data['cpostal']   = $paciente->cpostal;
        $data['poblacion'] = $paciente->poblacion;
        $data['provincia'] = $paciente->provincia;
        $data['cif']       = $paciente->dni;

        $fecha = Carbon::parse($data['fecha']);
        $data['ejercicio']    = $fecha->format('Y');

        $contador = Factura::whereYear('fecha', $data['ejercicio'])
                            ->where('serie', 'M')
                            ->orderBy('factura','desc')
                            ->first();
        if ($contador == '')
            $factura = 1;
        else
            $factura = $contador->factura + 1;



        $data['factura']  = $factura;
        $data['serie']    = 'M';

        $data['fpago_id'] = 1;

        // if ($data['fpago_id'] == 2){
        //     $iban = Cuenta::defecto()->first();
        //     if ($iban != null)
        //         $data['cuenta_id'] = $iban->id;
        // }

        $data['empresa_id'] =  session()->get('empresa')->id;
        $data['username']   = session()->get('username');


        $reg = Factura::create($data);

        if (request()->wantsJson())
            return [
                'factura'=>$reg,
                'message' => 'EL registro ha sido creado'
            ];
    }

      /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Factura $factura)
    {

        $this->authorize('update', $factura);

        if (request()->wantsJson())
            return [
                'factura' => $factura->load(['paciente','cuenta','fpago']),
                'cuentas' => Cuenta::selCuentas(),
                'fpagos'  => Fpago::selFpagos(),
            ];

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFacturaRequest $request, Factura $factura)
    {

       // $this->authorize('update', $paciente);
        $data = $request->validated();


        $data['username'] = $request->user()->username;

        $factura->update($data);

        if (request()->wantsJson())
            return [
                'factura' => $factura->load(['paciente','cuenta','fpago']),
                'message' => 'EL registro ha sido modificado'
                ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Factura $factura)
    {

        $this->authorize('delete', $factura);

        $factura->delete();

        if (request()->wantsJson()){
            return  response('Registro borrado!', 200);
        }
    }



     /**
     * Recibe las facturas por request, previamente de $this->lisfac()
     *
     * @param Request $request
     * @return void
     */
    public function excel(Request $request){

  //      return Excel::download(new AlbaranesExport($request->data), 'albaranes.xlsx');

    }




}
