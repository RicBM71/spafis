<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\WellcomeController::class, 'index'])->name('wellcome');
Route::get('/tpvpc/{empresa_id}/{tpv_id}/{importe}/{paciente_id}', [App\Http\Controllers\Citas\TpvPcController::class, 'index']);
Route::post('/tpvpc/store', [App\Http\Controllers\Citas\TpvPcController::class, 'store']);


// Route::get('/test', function () {
//    dd(Carbon::parse('11:00')->betweenIncluded('10:00', '10:59'));
// });


Auth::routes();

// Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dash', [App\Http\Controllers\HomeController::class, 'dash'])->name('dash');

Route::group(['middleware' => ['auth']], function () {

    Route::post('/profile/avatar', [App\Http\Controllers\HomeController::class, 'avatar']);
    Route::put('/profile/avatar', [App\Http\Controllers\HomeController::class, 'destroy']);
    Route::post('/admin/users/password/update',[App\Http\Controllers\Admin\UsersController::class, 'updatePassword']);

});

Route::group([
    'prefix' => 'admin',
    // 'namespace' => 'Mto',
    'middleware' => ['auth','password','role:Admin']],
    function (){

        Route::resource('parametros', App\Http\Controllers\Admin\ParametrosController::class, ['only'=>['index','update'], 'as' => 'root']);
        Route::post('parametros/main', [App\Http\Controllers\Admin\ParametrosController::class, 'main']);
        Route::put('parametros/main/delete', [App\Http\Controllers\Admin\ParametrosController::class, 'deletemain']);
        Route::post('parametros/section', [App\Http\Controllers\Admin\ParametrosController::class, 'section']);
        Route::put('parametros/section/delete', [App\Http\Controllers\Admin\ParametrosController::class, 'deletesection']);

        Route::resource('roles', App\Http\Controllers\Admin\RolesController::class, ['as' => 'root']);
        Route::resource('permissions', App\Http\Controllers\Admin\PermissionsController::class);

        Route::resource('users', App\Http\Controllers\Admin\UsersController::class);

        Route::put('users/{user}/roles', [App\Http\Controllers\Admin\UsersRolesController::class, 'update']);

        Route::put('users/{user}/permissions', [App\Http\Controllers\Admin\UsersPermissionsController::class, 'update']);

        Route::put('users/{user}/empresas', [App\Http\Controllers\Admin\UsersEmpresasController::class, 'update']);

        Route::put('users/{user}/empresa', [App\Http\Controllers\Admin\UsersController::class, 'updateEmpresa']);
        Route::put('users/{user}/reset', [App\Http\Controllers\Admin\UsersController::class, 'reset']);

        Route::post('users/{user}/avatar', [App\Http\Controllers\Admin\AvatarsController::class, 'store']);
        Route::delete('avatars/{user}/delete', [App\Http\Controllers\Admin\AvatarsController::class, 'destroy']);

        Route::resource('empresas', App\Http\Controllers\Admin\EmpresasController::class, ['except'=>'show']);

        Route::post('empresas/{empresa}/logo', [App\Http\Controllers\Admin\EmpresasController::class, 'logo']);
        Route::put('empresas/{empresa}/logo/delete', [App\Http\Controllers\Admin\EmpresasController::class, 'deletelogo']);
        Route::post('empresas/{empresa}/fondo', [App\Http\Controllers\Admin\EmpresasController::class, 'fondo']);
        Route::post('empresas/{empresa}/fondo/delete', [App\Http\Controllers\Admin\EmpresasController::class, 'deletefondo']);

        Route::resource('ipusers', App\Http\Controllers\Admin\IpUsersController::class, ['only'=>['store','destroy', 'as' => 'admin']]);
        Route::resource('cuentas', App\Http\Controllers\Admin\CuentasController::class);

    }
);


Route::group([
    'prefix' => 'mto',
    // 'namespace' => 'Mto',
    'middleware' => ['auth','role:Admin']],
    function (){

        Route::post('facultativos/filtrar', [App\Http\Controllers\Mto\FacultativosController::class, 'filtrar']);
        Route::resource('facultativos', App\Http\Controllers\Mto\FacultativosController::class);

        Route::resource('areas', App\Http\Controllers\Mto\AreasController::class);
        Route::resource('almacene', App\Http\Controllers\Mto\AlmacenesController::class);
        Route::resource('apuntes', App\Http\Controllers\Mto\ApuntesController::class);
        Route::resource('bancos', App\Http\Controllers\Mto\BancosController::class);
        Route::resource('bonos', App\Http\Controllers\Mto\BonosController::class);

        Route::resource('horarios', App\Http\Controllers\Mto\HorariosController::class);
        Route::resource('ivas', App\Http\Controllers\Mto\IvasController::class);
        Route::resource('mutuas', App\Http\Controllers\Mto\MutuasController::class);
        Route::resource('tratamientos', App\Http\Controllers\Mto\TratamientosController::class);

        Route::resource('cajas', App\Http\Controllers\Mto\CajasController::class);
        Route::post('cajas/filtrar', [App\Http\Controllers\Mto\CajasController::class, 'filtrar']);
        Route::post('cajas/cerrar', [App\Http\Controllers\Mto\CajasController::class, 'cerrar']);
        Route::post('cajas/saldo', [App\Http\Controllers\Mto\CajasController::class, 'saldo']);
        Route::post('cajas/excel', [App\Http\Controllers\Mto\CajasController::class, 'excel']);

        Route::resource('festivos', App\Http\Controllers\Mto\FestivosController::class);
        Route::resource('bloqueos', App\Http\Controllers\Mto\BloqueosController::class);

        Route::post('pacientes/{paciente}/foto', [App\Http\Controllers\Mto\PacientesController::class, 'foto']);
        Route::post('pacientes/capture', [App\Http\Controllers\Mto\PacientesController::class, 'capture']);
        Route::post('pacientes/delcap', [App\Http\Controllers\Mto\PacientesController::class, 'delcap']);


    }
);


Route::group([
    'prefix' => 'mto',
    // 'namespace' => 'Mto',
    'middleware' => ['auth']],
    function (){


        Route::resource('pacientes', App\Http\Controllers\Mto\PacientesController::class);
        Route::post('pacientes/filtrar', [App\Http\Controllers\Mto\PacientesController::class, 'filtrar']);

        Route::resource('historias', App\Http\Controllers\Mto\HistoriasController::class);
        Route::resource('pacbonos', App\Http\Controllers\Mto\PacbonosController::class);
        Route::resource('adjuntos', App\Http\Controllers\Mto\AdjuntosController::class, ['only'=>['show','update','destroy']]);
        Route::post('adjuntos/{paciente_id}/upload', [App\Http\Controllers\Mto\AdjuntosController::class,'upload']);

        Route::resource('pacbonos', App\Http\Controllers\Mto\PacbonosController::class);

    }
);



Route::group([
    'prefix' => 'citas',
    // 'namespace' => 'Mto',
    'middleware' => ['auth','password']],
    function (){

        Route::resource('citas', App\Http\Controllers\Citas\CitasController::class);
        Route::resource('hcitas', App\Http\Controllers\Citas\HCitasController::class, ['only'=>['show']]);

        Route::post('add', [App\Http\Controllers\Citas\CitasController::class, 'add']);
        Route::post('addpro', [App\Http\Controllers\Citas\CitasController::class, 'addpro']);
        Route::post('reload', [App\Http\Controllers\Citas\CitasController::class, 'reload']);
        Route::post('filtro', [App\Http\Controllers\Citas\CitasController::class, 'filtro']);

        Route::put('{cita}/estado', [App\Http\Controllers\Citas\CitasController::class, 'estado']);
        Route::get('{paciente_id}/create', [App\Http\Controllers\Citas\CitasController::class, 'create']);

        Route::put('/tratado', [App\Http\Controllers\Citas\CitasController::class, 'tratado']);

        Route::put('/reasignar', [App\Http\Controllers\Citas\CitasController::class, 'reasignar']);

        Route::post('cobro/load', [App\Http\Controllers\Citas\CobroCitasController::class, 'load']);
        Route::post('cobro/cobrar', [App\Http\Controllers\Citas\CobroCitasController::class, 'submit']);
        Route::get('recibo/{paciente_id}/{fecha}', [App\Http\Controllers\Citas\CobroCitasController::class, 'recibo']);
        Route::post('cobro/directo', [App\Http\Controllers\Citas\CobroCitasController::class, 'directo']);

        Route::get('cobros', [App\Http\Controllers\Citas\CobroCitasController::class, 'index']);
        Route::post('cobro/cancelar', [App\Http\Controllers\Citas\CobroCitasController::class, 'cancelar']);
        Route::get('cobros/{cita_id}/show', [App\Http\Controllers\Citas\CobroCitasController::class, 'show']);

        Route::put('tpvmov/update', [App\Http\Controllers\Citas\TpvMovController::class, 'update']);

        Route::get('ticket/{paciente_id}/{fecha}', [App\Http\Controllers\Citas\TpvMovController::class, 'recibo']);

        Route::get('ultimas/{paciente_id}', [App\Http\Controllers\Citas\CitasController::class, 'ultimasCitas']);
        Route::get('bono/{numero_bono}', [App\Http\Controllers\Citas\CitasController::class, 'citasBono']);

        Route::get('recordar/{paciente_id}', [App\Http\Controllers\Citas\PrintCitasController::class, 'recordar']);

        Route::get('/print', [App\Http\Controllers\Citas\PrintCitasController::class, 'index']);
        //Route::post('/print/listas', [App\Http\Controllers\Citas\PrintCitasController::class, 'listas']);
        //Route::get('/print/listas/{area_id}/{turno}/{fecha}/facultativo_id', [App\Http\Controllers\Citas\PrintCitasController::class, 'listas']);
        Route::get('/print/listas/{area_id}/{fecha}/{turno}/{facultativo_id?}', [App\Http\Controllers\Citas\PrintCitasController::class, 'listas']);

        Route::get('justificante/{id?}', [App\Http\Controllers\Citas\PrintJustificanteController::class, 'show']);
        Route::post('justificante/print', [App\Http\Controllers\Citas\PrintJustificanteController::class, 'print']);

    }
);


Route::group([
    'prefix' => 'facturas',
    // 'namespace' => 'Mto',
    'middleware' => ['auth','password','role:Admin']],
    function (){
        Route::resource('facturas', App\Http\Controllers\Facturas\FacturasController::class);
        Route::resource('factlins', App\Http\Controllers\Facturas\FactlinsController::class);
        Route::post('factlins/facturar', [App\Http\Controllers\Facturas\FactlinsController::class, 'facturar']);
        Route::get('print/{id}', [App\Http\Controllers\Facturas\PrintFacturasController::class, 'print']);
        Route::get('mail/{factura}', [App\Http\Controllers\Facturas\PrintFacturasController::class, 'mail']);

        Route::get('facturacion', [App\Http\Controllers\Facturas\FacturacionController::class, 'index']);
        Route::post('facturacion', [App\Http\Controllers\Facturas\FacturacionController::class, 'submit']);
    }
);

Route::group([
    'prefix' => 'consultas',
    'middleware' => ['auth','password','role:Admin']],
    function (){
        Route::get('cobrosmes', [App\Http\Controllers\Consultas\CobrosMesController::class, 'index']);
        Route::post('cobrosmes', [App\Http\Controllers\Consultas\CobrosMesController::class, 'submit']);
        Route::post('cobrosmes/excel', [App\Http\Controllers\Consultas\CobrosMesController::class, 'excel']);

        Route::get('comparativo', [App\Http\Controllers\Consultas\ComparativoController::class, 'index']);
        Route::post('comparativo', [App\Http\Controllers\Consultas\ComparativoController::class, 'submit']);
        Route::post('comparativo/excel', [App\Http\Controllers\Consultas\ComparativoController::class, 'excel']);
    }
);


// Route::group([
//     'prefix' => 'tools',
//     'middleware' => ['auth']],
//     function (){


//     }
// );


Route::group([
    'prefix' => 'tools',
    'namespace' => 'Tools',
    'middleware' => ['auth','password']],
    function (){

        Route::get('get/{cadena}', [App\Http\Controllers\Tools\HelpPacienteController::class, 'get']);
        Route::post('helpcli', [App\Http\Controllers\Tools\HelpPacienteController::class, 'index']);
        Route::get('helpcli/{paciente_id}/bono', [App\Http\Controllers\Tools\HelpPacienteController::class, 'bono']);

        Route::post('compartir/bono', [App\Http\Controllers\Tools\CompartirBonoController::class, 'submit']);
        Route::post('compartir/locate', [App\Http\Controllers\Tools\CompartirBonoController::class, 'locate']);
        Route::post('compartir/multiple', [App\Http\Controllers\Tools\CompartirBonoController::class, 'multiple']);

        Route::post('valorar', [App\Http\Controllers\Tools\HelpCitasController::class, 'valorar']);
        Route::post('citas/huecos', [App\Http\Controllers\Tools\HelpCitasController::class, 'huecos']);
        Route::post('citas/bono/anticipo', [App\Http\Controllers\Tools\HelpCitasController::class, 'anticipo']);
        Route::post('citas/vendibles', [App\Http\Controllers\Tools\HelpCitasController::class, 'vendibles']);
        Route::post('citas/facturables', [App\Http\Controllers\Tools\HelpCitasController::class, 'facturables']);

        Route::get('pacientes/export', [App\Http\Controllers\Tools\PacientesExportController::class, 'export']);

        Route::get('sms/send/{fecha}', [App\Http\Controllers\Tools\SmsController::class, 'index']);

        Route::post('sms/send', [App\Http\Controllers\Tools\SmsController::class, 'send']);
        Route::get('sms/{cita_id}/sendone', [App\Http\Controllers\Tools\SmsController::class, 'sendone']);
        Route::get('sms/{cita}/cancel', [App\Http\Controllers\Tools\SmsController::class, 'cancelSMS']);

        Route::post('helpcli', [App\Http\Controllers\Tools\HelpPacienteController::class,'index']);
        Route::post('compartir/bono', [App\Http\Controllers\Tools\CompartirBonoController::class,'submit']);
        Route::post('compartir/locate', [App\Http\Controllers\Tools\CompartirBonoController::class,'locate']);
        Route::post('compartir/multiple', [App\Http\Controllers\Tools\CompartirBonoController::class, 'multiple']);

        Route::get('reasignar/{pacbono}', [App\Http\Controllers\Tools\AsignarBonoController::class, 'asignar']);

    }
);


Route::group([
    'prefix' => 'pdf',
    'namespace' => 'Pdf',
    'middleware' => ['auth','password','role:Admin']],
    function (){

        Route::get('lortad/{paciente}', [App\Http\Controllers\Pdf\PrintLortadController::class, 'print']);

    }
);



 //Route::get('/{all}', [WellcomeController::class, 'index'])->where('all', '.*');

 Route::get('/{any}', [App\Http\Controllers\WellcomeController::class, 'index'])->where('any', '.*');

// Route::get('/{any}', function () {
//     return view('welcome');
// })->where('any', '.*');


// Route::any('{all}', function () {
//     return view('welcome');
// })->where(['all' => '.*']);

