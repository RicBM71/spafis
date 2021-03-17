<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Paciente extends Model
{

    protected $fillable = [

                'nombre',
                'apellidos',
                'direccion',
                'cpostal',
                'poblacion',
                'provincia',
                'telefono1',
                'telefono2',
                'telefonom',
                'notificar',
                'texto_tf2',
                'email',
                'cif',
                'sexo',
                'fecha_nacimiento',
                'descuento',
                'porcentual',
                'profesion',
                'tarifa_reducida',
                'mutua_id',
                'fecha_baja',
                'exportar',
                'riesgo',
                'notas1',
                'notas2',
                'medio_id',
                'recomendado_id',
                'ant1',
                'ant2',
                'ant3',
                'ant4',
                'ant5',
                'ant6',
                'antobs',
                'embarazada',
                'peso',
                'altura',
                'lortad_evo',
                'lortad_fide',
                'notas_adm',
                'espera',
                'factura_auto',
                'username'

        ];

        protected $appends = ['nom_ape','edad', 'foto', 'cumple','ape'];

        public function getNomApeAttribute(){

            return $this->nombre." ".$this->apellidos;

        }

        public function getFotoAttribute(){

            $foto = '/fotos/'.$this->id.'.jpg';

            return Storage::disk('public')->exists($foto) ? '/storage'.$foto : false;

        }

        public function getEdadAttribute(){

            if ($this->fecha_nacimiento==null) return 0;

            return Carbon::parse($this->fecha_nacimiento)->age;

            // list($anio,$mes,$dia) = explode("-",$this->fecha_nacimiento);

            // $anio_dif = date("Y") - $anio;
            // $mes_dif = date("m") - $mes;
            // $dia_dif = date("d") - $dia;

            // //if ($dia_dif < 0 && $mes_dif < 0)
            //     //$anio_dif--;

            // if ($mes_dif < 0)
            //    $anio_dif--;
            // else if ($dia_dif < 0 && $mes_dif == 0)
            //       $anio_dif--;



            // return $anio_dif;

        }

        public function getApeAttribute(){


            $ape = explode(" ", $this->apellidos);

            if (count($ape) >= 2){
                // devolmenmos el complement más siguiente palabra: DEL CASTILLO
                if ($ape[0] == "DEL" || $ape[0] == "DE"){
                    $ap = $ape[0].' '.$ape[1];
                }
                else{
                    $ap = $ape[0];
                }
            }else{
                $ap = $this->apellidos;
            }

            return $this->nombre.' '.$ap;
        }

        public function getCumpleAttribute(){

            $cumple = '';

            $today = Carbon::today();

            if (substr($this->fecha_nacimiento,5,6) == date('m-d')){
                $cumple = 'Felicitar!';
            }else if (substr($this->fecha_nacimiento,5,2) == date('m')){

                $fecha_cumple = $today->format('Y').'-'.substr($this->fecha_nacimiento,5,6);
                $fecha_cumple = Carbon::parse($fecha_cumple);

                $dif = $today->diffInDays($fecha_cumple);

                if ($dif <= -7 && $dif >= 7)
                    $cumple = 'Cumple el '.(int) substr($this->fecha_nacimiento,8,2);
            }

            return $cumple;
        }

        public function historias()
        {
            return $this->hasMany(Historia::class);
        }

        public function facturas()
        {
            return $this->hasMany(Factura::class);
        }


        public function pacbonos()
        {
            return $this->hasMany(Pacbono::class);
        }

        public function adjuntos()
        {
            return $this->hasMany(Adjunto::class);
        }

        public function citas()
        {
            return $this->hasMany(Adjunto::class);
        }

        public function medio()
        {
    	    return $this->belongsTo(Medio::class);
        }



        public function scopeRazon($query, $razon){

            if (!Empty($razon)){


                if (strpos($razon,',')!==false){

                    if (strpos($razon,',') !== false){

                        $data_exp = explode(",", $razon);

                        if ($data_exp[0] != '' && $data_exp[1] != ''){
                            $query->where('nombre','like',$data_exp[0].'%')
                                  ->where('apellidos','like',$data_exp[1].'%');
                        }elseif ($data_exp[0] != ''){
                            $query->where('nombre','like',$data_exp[0].'%');
                        }elseif ($data_exp[1] != ''){
                            $query->where('apellidos','like',$data_exp[1].'%');
                        }

                        return $query;

                    }


                    // $cli = explode(",", $razon);
                    // $query->where('nombre','like',$cli[0].'%')
                    //       ->where('apellidos','like',$cli[1].'%');
                }else if(strpos($razon,'.') !== false){

                    $bono = substr($razon,1);

                    $query->whereIn('id', function($query) use ($bono) {
                                $query->select('paciente_id')
                                        ->from('pacbonos')->where('bono', $bono);
                            });
                }else if(strpos($razon,'+') !== false){

                    if (!hasContact() || strlen($razon) <= 9) return false;

                    $tf = substr($razon,1);
                    $query->where('telefono1', 'like', $tf.'%')
                        ->orWhere('telefono2', 'like', $tf.'%')
                        ->orWhere('telefonom', 'like', $tf.'%');

                }else{
                    $query->where('apellidos','like','%'.$razon.'%');
                }
            }

            return $query;

        }

        public function scopeNombre($query, $texto){


            if (Empty($texto)) return $query;

            if (strpos($texto,',') !== false){

                $data_exp = explode(",", $texto);

                if ($data_exp[0] != '' && $data_exp[1] != ''){
                    $query->where('nombre','like',$data_exp[0].'%')
                          ->where('apellidos','like',$data_exp[1].'%');
                }elseif ($data_exp[0] != ''){
                    $query->where('nombre','like',$data_exp[0].'%');
                }elseif ($data_exp[1] != ''){
                    $query->where('apellidos','like',$data_exp[1].'%');
                }

                return $query;

            }

            return $query->where('apellidos','like',$texto.'%');

        }

        public function scopeDireccion($query, $texto){

            if (Empty($texto)) return $query;

            return $query->where('direccion','like',$texto.'%');
        }

        public function scopeTelefono($query, $texto){

            if (Empty($texto)) return $query;

            return $query->where('telefono1','like',$texto.'%')
                  ->OrWhere('telefono2','like',$texto.'%')
                  ->OrWhere('telefonom','like',$texto.'%');

        }

        public function scopeEspera($query, $espera){

            return ($espera == true) ? $query->where('espera', $espera) : $query;

        }

    public function setNombreAttribute($nombre)
    {
        $this->attributes['nombre'] = strtoupper($nombre);

    }

    public function setTextotf2Attribute($texto_tf2)
    {
        $this->attributes['texto_tf2'] = strtoupper($texto_tf2);

    }

    public function setApellidosAttribute($apellidos)
    {
        $this->attributes['apellidos'] = strtoupper($apellidos);

    }
    public function setDireccionAttribute($direccion)
    {
        $this->attributes['direccion'] = strtoupper($direccion);

    }
    public function setCpostalAttribute($cpostal)
    {
        $this->attributes['cpostal'] = strtoupper($cpostal);

    }

    public function setPoblacionAttribute($poblacion)
    {
        $this->attributes['poblacion'] = strtoupper($poblacion);

    }

    public function setProvinciaAttribute($provincia)
    {
        $this->attributes['provincia'] = strtoupper($provincia);

    }

    public function setDniAttribute($dni)
    {
        $this->attributes['dni'] = strtoupper($dni);

    }



        public static function selPacienteId($id)
        {

            return Paciente::select(DB::raw('id AS value, CONCAT(nombre," ",apellidos) AS text'))
                ->where('id', $id)
                ->orderBy('nombre', 'asc')
                ->get();

        }

}
