<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Authip;
use App\Models\Ipuser;
use App\Models\Empresa;
use App\Models\Parametro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function username()
    {
        return 'username';
    }



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

     /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {

        $ret = $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );

        if ($ret == false)
            return $ret;

        if ($this->checkIp($request) == false){
            $this->guard()->logout();

            $request->session()->invalidate();
            throw ValidationException::withMessages([
                $this->username() => ['Acceso por IP bloqueado'],
            ]);

        }

        return $ret;
    }


    private function checkIp($request){

        if (esRoot() || esAdmin()){
            Authip::updateOrCreate(['id' => $request->ip()]);
            return true;
        }

        $user = auth()->user();

        if (Authip::where('id',$request->ip())->get()->count() == 0){
            activity()
            ->causedBy($user)
            ->withProperties([
                'username' => $user->username,
                'ip' => $request->ip()
            ])
            ->log('IP LOCK');


            return false;
        }

        // $ips = Ipuser::getIpuser($request->user()->id);
        // if ($ips != null){
        //     if (!in_array($request->ip(), $ips)) {
        //         return false;
        //     }
        // }

        return true;

    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {

        return $request->only($this->username(), 'password', 'blocked');
    }



    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {

        activity()
            ->causedBy($user)
            ->withProperties([
                'username' => $user->username,
                'ip' => $request->ip()
            ])
            ->log('Login');

        $data['login_at'] = date('Y-m-d H:i:s');
        $user->update($data);
    }

    private function perfil($user){


        // empresa seleccionada
        $e = DB::table('empresa_user')->select('empresa_id')
                                    ->where('user_id', $user->id)
                                    ->get()->first();
        $empresa_id = $e->empresa_id;

        $empresa = Empresa::findOrFail($empresa_id);

        $user = [
            'id'            => $user->id,
            'name'          => $user->name,
            'username'      => $user->username,
            'avatar'        => $user->avatar,
            'empresa_id'    => $empresa_id,
        ];



        session([
            'empresa_id'       => $empresa_id,
            'empresa'          => Empresa::find($empresa_id),
            ]);

    }
}
