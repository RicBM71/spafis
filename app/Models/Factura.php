<?php

namespace App\Models;

use App\Observers\FacturaObserver;
use App\Scopes\EmpresaActivaScope;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable  = [
        'empresa_id' ,
        'ejercicio'  ,
        'factura'    ,
        'serie'      ,
        'fecha'      ,
        'paciente_id',
        'mutua_id'   ,
        'razon'      ,
        'direccion'  ,
        'poblacion'  ,
        'cpostal'    ,
        'provincia'  ,
        'cif'        ,
        'cuenta_id'  ,
        'fpago_id'   ,
        'notas',
        'username'
    ];

    protected $dates = ['fecha'];
    protected $appends = ['ser_fac'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaActivaScope);

        Factura::observe(FacturaObserver::class);

    }

    public function paciente()
    {
    	return $this->belongsTo(Paciente::class);
    }

    public function cuenta()
    {
    	return $this->belongsTo(Cuenta::class);
    }

    public function mutua()
    {
    	return $this->belongsTo(Mutua::class);
    }

    public function fpago()
    {
    	return $this->belongsTo(Fpago::class);
    }

    public function factlins(){
        return $this->hasMany(Factlin::class);
    }

    public function getSerFacAttribute(){

        return $this->serie.$this->factura;
    }

    // public function setFacturaAttribute($factura)
    // {
    //     $l = strlen($factura);

    //     $this->attributes['factura'] = ($this->ejercicio - 2000).str_repeat('0', 4-$l).$factura;
    // }

    public function setRazonAttribute($razon)
    {
        $this->attributes['razon'] = strtoupper($razon);
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

    public function setCifAttribute($cif)
    {
        $this->attributes['cif'] = strtoupper($cif);

    }


}
