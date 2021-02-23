<?php

namespace App\Models;

use Carbon\Carbon;
use App\Scopes\EmpresaActivaScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $fillable = [
        'empresa_id', 'fecha', 'dh', 'nombre', 'importe', 'manual','cita_id','paciente_id','username'
    ];

    /**
     *
     * Añadimos global scope para filtrado por empresa.
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaActivaScope);
    }

    public function setImporteAttribute($value)
    {
        $this->attributes['importe'] = round($value,2);
    }

    public function scopeRangoFechas($query, $d, $h){

        $query->where('fecha','>=',$d);
        $query->where('fecha','<=',$h);

        return $query;

    }
    public function scopeDh($query, $dh){

        if ($dh != null)
            $query->where('dh',$dh);

        return $query;

    }
    public function scopeManual($query, $manual){

        if ($manual != null)
            $query->where('manual',$manual);

        return $query;

    }


    public static function saldo($fecha){

        $f = Carbon::parse($fecha)->format('Y-m-d');
        $ejercicio = Carbon::parse($fecha)->format('Y');

        $saldo = 0;

        $data = DB::table('cajas')
                ->selectRaw('dh, SUM(importe) AS importe')
                ->where('empresa_id', session('empresa_id'))
                ->groupBy('dh')
                ->whereYear('fecha', $ejercicio)
                ->where('fecha', '<=', $f)
                ->get();

        foreach ($data as $row){
            $valor = ($row->dh == 'D') ? $row->importe * -1 : $row->importe;
            $saldo += $valor;
        }

        return round($saldo,2);

    }

    public static function saldoByEmpresa($empresa_id, $fecha){

        $f = Carbon::parse($fecha)->format('Y-m-d');
        $ejercicio = Carbon::parse($fecha)->format('Y');

        $saldo = 0;

        $data = DB::table('cajas')
                ->selectRaw('dh, SUM(importe) AS importe')
                ->groupBy('dh')
                ->where('empresa_id', $empresa_id)
                ->whereYear('fecha', $ejercicio)
                ->where('fecha', '<=', $f)
                ->get();

        foreach ($data as $row){
            $valor = ($row->dh == 'D') ? $row->importe * -1 : $row->importe;
            $saldo += $valor;
        }

        return $saldo;

    }


}
