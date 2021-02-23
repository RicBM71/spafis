<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Iva;
use App\Models\Tpv;
use App\Models\Fpago;
use App\Models\Estado;
use App\Models\Almacen;
use App\Models\Empresa;
use App\Models\Festivo;
use App\Models\Categoria;
use App\Models\Parametro;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenericasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Empresa::truncate();
        Almacen::truncate();
        FPago::truncate();
        Iva::truncate();
        Estado::truncate();

        DB::table('empresa_user')->truncate();

        $reg = DB::connection('db2')->select('select * from empresa');

        foreach($reg as $row){

            $data[]=array(
                'id'        => $row->id,
                'nombre'    => $row->nombre,
                'razon'     => $row->razon,
                'titulo'    => 'Sanaval',
                'cif'       => $row->cif,
                'poblacion' => $row->poblacion,
                'direccion' => $row->direccion,
                'cpostal'   => $row->cpostal,
                'provincia' => $row->provincia,
                'telefono1' => $row->telefono,
                'email'     => $row->email,
                'ult_bono'  => $row->bono,
                'txtpie1'   => $row->txtpie1,
                'txtpie2'   => $row->txtpie2,
                'sms_usr'   => $row->smsusr,
                'sms_password'=> $row->smspasswd,
                'sms_api'     =>'b7a5a919574346a2b67a3a5cb2c18bad',
                'sms_sender'  =>$row->smssender,
                'sms_pais'   =>$row->smspais,
                'sms_zona'   =>$row->smszona,
                'sms_am'     =>$row->smsam,
                'sms_pm'     =>$row->smspm,
                'ccc_ss'     =>$row->cccss,
                'tpv'        =>true,
                'disco'      =>'disco1',
                'created_at' => $row->sysfum.' 00:00:00',
                'updated_at' => $row->sysfum.' '.$row->syshum,
                'username'   => $row->sysusr
            );

        }

        DB::table('empresas')->insert($data);



    //     $emp = new Empresa;
    //     $emp->nombre = "Demo";
    //     $emp->razon = "Demo";
    //     $emp->cif="B82848417";
    //     $emp->titulo = "Sanaval";
    //    // $emp->logo = "logo.jpg";
    //     $emp->certificado = "";
    //     $emp->passwd_cer="";
    //     $emp->flags='11111000000000000000';
    //     $emp->save();


        $alm = new Almacen;
        $alm->empresa_id=1;
        $alm->nombre = "Almacén 1";
        $alm->save();

        DB::table('empresa_user')->insert(
            ['empresa_id' => 1, 'user_id' => '1'],
            ['empresa_id' => 1, 'user_id' => '2']
        );

        $fp = new Fpago;
        $fp->id =1 ;
        $fp->nombre = "Efectivo";
        $fp->save();

        $fp = new Fpago;
        $fp->id =4 ;
        $fp->nombre = "Transferencia";
        $fp->save();

        $fp = new Fpago;
        $fp->id =3 ;
        $fp->nombre = "Talón";
        $fp->save();

        $fp = new Fpago;
        $fp->id =2 ;
        $fp->nombre = "Tarjeta";
        $fp->save();


        $par = new Parametro;
        $par->pie_rebu1 = "Dispone de un plazo máximo de 15 días para su devolución. No se reembolsará dinero.";
        //$par->img1 = 'hero.jpeg';
        $par->save(); // con valores defecto migración de momento.

        $reg = new Iva;
        $reg->nombre = "General";
        $reg->importe = 21;
        $reg->save();

        $reg = new Iva;
        $reg->nombre = "Exento IVA";
        $reg->importe = 0;
        $reg->leyenda="Actividad exenta de IVA s/Art. 20 IVA.";
        $reg->save();

        $cat = new Categoria;
        $cat->nombre = "Fisioterapueta";
        $cat->save();

        $cat = new Categoria;
        $cat->nombre = "Servicios";
        $cat->save();

        $fp = new Estado;
        $fp->id =1 ;
        $fp->nombre = "Pendiente";
        $fp->color = "orange--text darken-4";
        $fp->save();

        $fp = new Estado;
        $fp->id =2 ;
        $fp->nombre = "Tratado";
        $fp->color = "blue--text darken-1";
        $fp->save();

        $fp = new Estado;
        $fp->id =3 ;
        $fp->nombre = "Pagada";
        $fp->color = "green--text darken-4";
        $fp->save();

        $fp = new Estado;
        $fp->id =4 ;
        $fp->nombre = "Cancelada";
        $fp->color = "red--text darken-4";
        $fp->save();


        // $mot = new Motivo();
        // $mot->nombre = "Falta de información importante en el documento";
        // $mot->save();

        // $mot = new Motivo();
        // $mot->nombre = "Error o ausencia en los datos profesionales";
        // $mot->save();

        // $mot = new Motivo();
        // $mot->nombre = "IVA erróneo";
        // $mot->save();

        // $mot = new Motivo();
        // $mot->nombre = "Importes erroneos";
        // $mot->save();

        // $mot = new Motivo();
        // $mot->nombre = "Devolución de productos";
        // $mot->save();

        // $mot = new Motivo();
        // $mot->nombre = "Otros";
        // $mot->save();

        Tpv::truncate();

        $reg = DB::connection('db2')->select('select * from tpv WHERE id > 0');

        $data = array();

        foreach($reg as $row){


            $data[]=array(
                'id'        => $row->id,
                'nombre'    => $row->nombre,
                'comercio'       => $row->comercio,
                'puerto' => $row->puerto,
                'terminal' => $row->terminal,
                'version'   => $row->version,
                'firma' => $row->firma,
                'nombre_comercio' => $row->nomemp,
                'created_at' => ($row->sysfum != "0000-00-00" && $row->sysfum != null) ? $row->sysfum.' 00:00:00' : $row->sysfum.' '.$row->syshum,
                'updated_at' => ($row->sysfum != "0000-00-00" && $row->sysfum != null) ? $row->sysfum.' 00:00:00' : $row->sysfum.' '.$row->syshum,
                'username'   => $row->sysusr
            );

        }

        DB::table('tpvs')->insert($data);


        Festivo::truncate();

        $reg = DB::connection('db2')->select('select * from festivos');
        $data = array();
        $dt = Carbon::now();
        foreach($reg as $row){


            $data[]=array(
                'fecha'    => $row->fecha,
                'username' => 'sys',
                'updated_at' => $dt,
                'created_at' => $dt,
            );

        }

        DB::table('festivos')->insert($data);



    }
}
