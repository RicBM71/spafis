<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cita;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Festivo;
use App\Models\Pacbono;
use App\Models\Parametro;
use App\Models\Facultativo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

         return view('spa');
    }

    public function dash(Request $request)
    {

        $authUser = $request->user();

        // $admin = ($request->user()->hasRole('Root') || $request->user()->hasRole('Admin'));

        $role_user=[];
        $data = User::find($authUser->id)->roles()->get();
        foreach($data as $role){
            $role_user[]=$role->name;
        }

        $permisos_user=[];
        //$data = User::find($authUser->id)->permissions()->get();
        $data = auth()->user()->getAllPermissions();
        foreach($data as $permiso){
            $permisos_user[]=$permiso->name;
        }

        $empresas_usuario = collect();
        foreach ($authUser->empresas as $empresa){
            if ($empresa->flags[0] == false)
                continue;

            $empresa_id = $empresa->id;

            $empresas_usuario->push($empresa->id);
            $empresas[] = [
                'value' => $empresa->id,
                'text' => $empresa->titulo
            ];
        }


        $parametros = Parametro::find(1);

        // empresa seleccionada
        // $e = DB::table('empresa_user')->select('empresa_id')
        //                             ->where('user_id', $authUser->id)
        //                             ->get()->first();
        $empresa_id = 1;

        $empresa = Empresa::findOrFail($empresa_id);

        $titulo = App::environment('local') ? 'DESARROLLO' : $empresa->titulo;

        $user = [
            'id'            => $authUser->id,
            'name'          => $authUser->name,
            'username'      => $authUser->username,
            'avatar'        => $authUser->avatar,
            'empresa_id'    => $empresa_id,
            'empresa_nombre'=> $titulo,
            'roles'         => $role_user,
            'permisos'      => $permisos_user,
            'empresas'      => $empresas,
            'parametros'    =>$parametros,
            'img_fondo'     => $empresa->img_fondo,
            'facultativo_id'=> $authUser->facultativo_id
        ];

       // de momento no quito filtros, ya veremos.
        $this->unloadSession($request);

        $jobs  = DB::table('jobs')->count();


        $ses = $this->asignarObjetivo($authUser->facultativo_id);

        // quito restricción
        $facultativo_id = esSupervisor() ? null : $authUser->facultativo_id;

        session([
            'empresa_id'       => $empresa_id,
            'empresa'          => Empresa::find($empresa_id),
            'facultativo_id'   => $facultativo_id,
            'username'         => $authUser->username,
            'empresas_usuario' => $empresas_usuario,
            'parametros'       => $parametros,
            ]);

        $sms = $this->checkSMS();

        $this->asignarBonos();


        if (request()->wantsJson())
            return [
                'sesiones'  => $ses,
                'user'      => $user,
                'expired'   => $this->verificarExpired($request),
                'authuser'  => $authUser,
                'jobs'      => $jobs,
                'sms'       => $sms,
            ];
    }

    private function asignarObjetivo($facultativo_id){

        if ($facultativo_id != null) return 0;


        $f = Facultativo::find($facultativo_id)->get();

        if ($f->count() > 0){

            $f = $f->first();

            $sesiones =  DB::table('citas')
                            ->whereYear('fecha',date('Y'))
                            ->whereMonth('fecha',date('m'))
                            ->whereDate('fecha', '<=', date('Y-m-d'))
                            ->where('facultativo_id', 23)
                            ->whereIn('estado_id',[2,3])
                            ->get()
                            ->count();

            $ses = $sesiones >= $f->objetivo ? 100 : round($sesiones * 100 / $f->objetivo, 0);

        }else{
            $ses = 0;
        }

        return $ses;

    }

    private function checkSms(){

        if (App::environment('local')) return false;
        // establecer el primer hábil siguiente a la fecha de hoy

        $dt = Carbon::today();

        $habil = false;
        while ($habil == false) {

            $dt = $dt->addDays(1);
            if ($dt->dayOfWeekIso >= 6)
                continue;


            $f = Festivo::whereDate('fecha', $dt)->get()->count();

            if ($f > 0)
                continue;

            $habil = true;

        }

        $citas = Cita::join('pacientes','pacientes.id','=','paciente_id')
                        ->whereDate('fecha', $dt)
                        ->where('estado_id', '<>', 4)
                        ->where('notificar', 'S')
                        ->whereNull('envio_sms')
                        ->select('citas.id','nombre', 'telefonom', 'fecha', 'hora')
                        ->orderBy('hora')
                        ->get()
                        ->count();

        return [
            'sms'   => $citas,
            'fecha' => $dt->format('Y-m-d')
        ];

    }

    public function avatar(Request $request){

        $request->validate([
    		'avatar' => 'required|image|max:256'	//jpeg png, gif, svg
    	]);

        $user = $request->user();

    	$foto = request()->file('avatar')->store('avatars','public');


    	$fotoUrl = Storage::url($foto);

    	// 	//insert en la tabla photos
    	$user->update([
    	 	'avatar'	=> $fotoUrl,
    	 	'id'         => $user->id
        ]);

        return ['url'=>$fotoUrl];

    }

    public function destroy(Request $request)
    {

        $user = $request->user();

       $fotoPath = str_replace('storage', 'public', $user->avatar);
       $user->update([
            'avatar'    =>  null,
            'id'         => $user->id
        ]);

       // dd($fotoPath);

        Storage::delete($fotoPath);

        if (request()->wantsJson())
            return ['msg' => 'Avatar eliminado'];

    }

    /**
     *  Descarga todos los filtros al pasar por inicio
     */
    private function unloadSession($request){
        $data = $request->session()->all();
        foreach ($data as $key => $value){
            if (strstr($key, '_', true)=='filtro'){
                $request->session()->forget($key);
            }
        }
    }

    public function expired(){
    }

    public function verificarExpired($request){

        if ($request->user()->expira != 0 || is_null($request->user()->fecha_expira)){

            $f = Carbon::parse($request->user()->fecha_expira);
            $dias = $f->diffInDays(Carbon::now());

            if ($dias > ($request->user()->expira)  || is_null($request->user()->fecha_expira))
                return true;
        }
        return false;
    }

     /**
     *  Asigna los bonos a las citas del día y caduca si corresponde.
     *
     * @return void
     */
    private function asignarBonos(){

        $today = Carbon::today();

        // if (session('parametros')->ult_fecha_asigna_bono < $today->format('Y-m-d'))
        //     session('parametros')->update(['ult_fecha_asigna_bono' => $today]);
        // else
        //     return;

        $citas = Cita::with('tratamiento')
                    ->where('fecha', $today)
                    ->whereIn('estado_id',[1,2])
                    ->whereNull('bono')
                    ->get();

        foreach ($citas as $cita){

            $valores_bono = false;
            if (!$cita->tratamiento->bono)
                continue;

            $bonos_paciente = Pacbono::where('paciente_id',$cita->paciente_id)
                                    ->where('tratamiento_id', $cita->tratamiento_id)
                                    ->where('caducado', false)
                                    ->orderBy('fecha','asc')
                                    ->get();

            foreach ($bonos_paciente as $bono){

                if (bonoCaducado($bono, $today)){
                    continue;
                }

                $valores_bono = consumosBono($bono, $cita->tratamiento);
                if ($valores_bono == false){
                    $bono->update(['caducado'=>true]);
                    continue;
                }

            }


            //$valores_bono = consumosBono($bono, $cita->tratamiento);

            if ($valores_bono != false){
                DB::table('citas')->where('id',$cita->id)->update([
                    'importe'      => $valores_bono['importe'],
                    'importe_ponderado' => $valores_bono['importe_ponderado'],
                    'iva'          => $valores_bono['iva'],
                    'bono'         => $bono->bono,
                ]);
                // $cita->update([
                //     'importe'      => $valores_bono['importe'],
                //     'importe_ponderado' => $valores_bono['importe_ponderado'],
                //     'iva'          => $valores_bono['iva'],
                //     'bono'         => $bono->bono,
                // ]);
            }


        }

    }


}
