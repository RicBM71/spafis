<?php

namespace App\Http\Controllers\Consultas;

use Carbon\Carbon;
use App\Models\Area;
use Illuminate\Http\Request;
use App\Exports\CobrosMesExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class CobrosMesController extends Controller
{

    public function index(){


        if (request()->wantsJson())
            return [
                'areas'  => Area::selAreas(),
            ];

    }

    public function submit(Request $request){

        $data = $request->validate([
            'fecha'     => ['required','date'],
            'area_id'   => ['required','integer'],
        ]);

        if (request()->wantsJson())
            return $this->procesar($data);
    }

    private function procesar($data){

        $ejercicio = getEjercicio($data['fecha']);

        for ($i=0;$i<=3;$i++){
            $ejercicios[] = $ejercicio - $i;
        }

        $ejercicio_1 = $this->load($data['area_id'], $ejercicio, $data['fecha']);
        $ejercicio_2 = $this->load($data['area_id'], $ejercicio - 1, $data['fecha']);
        $ejercicio_3 = $this->load($data['area_id'], $ejercicio - 2, $data['fecha']);
        $ejercicio_4 = $this->load($data['area_id'], $ejercicio - 3, $data['fecha']);

        $f = Carbon::parse($data['fecha']);

        $data=array();
        for($i=1 ; $i<=12; $i++){

            $dif1_2 = $ejercicio_1[$i] - $ejercicio_2[$i];
            $por1_2 = $ejercicio_2[$i] != 0 ? round($dif1_2 / $ejercicio_2[$i] * 100, 2) : 0;

            $dif1_3 = $ejercicio_1[$i] - $ejercicio_3[$i];
            $por1_3 = $ejercicio_3[$i] != 0 ? round($dif1_3 / $ejercicio_3[$i] * 100, 2) : 0;

            $dif1_4 = $ejercicio_1[$i] - $ejercicio_4[$i];
            $por1_4 = $ejercicio_4[$i] != 0 ? round($dif1_4 / $ejercicio_4[$i] * 100, 2) : 0;

            $data[]=[
                'mes'       => mes($i),
                'eje1'      => $ejercicio_1[$i],
                'eje2'      => $ejercicio_2[$i],
                'dif1_2'    => $dif1_2,
                'por1_2'    => $por1_2,
                'eje3'      => $ejercicio_3[$i],
                'dif1_3'    => $dif1_3,
                'por1_3'    => $por1_3,
                'eje4'      => $ejercicio_4[$i],
                'dif1_4'    => $dif1_4,
                'por1_4'    => $por1_4,
            ];

            if ($i > 1){    // TOTALES
                $ejercicio_1[1]+=$ejercicio_1[$i];
                $ejercicio_2[1]+=$ejercicio_2[$i];
                $ejercicio_3[1]+=$ejercicio_3[$i];
                $ejercicio_4[1]+=$ejercicio_4[$i];
            }
        }

        $i = 1;
        $dif1_2 = $ejercicio_1[$i] - $ejercicio_2[$i];
        $por1_2 = $ejercicio_2[$i] != 0 ? round($dif1_2 / $ejercicio_2[$i] * 100, 2) : 0;

        $dif1_3 = $ejercicio_1[$i] - $ejercicio_3[$i];
        $por1_3 = $ejercicio_3[$i] != 0 ? round($dif1_3 / $ejercicio_3[$i] * 100, 2) : 0;

        $dif1_4 = $ejercicio_1[$i] - $ejercicio_4[$i];
        $por1_4 = $ejercicio_4[$i] != 0 ? round($dif1_4 / $ejercicio_4[$i] * 100, 2) : 0;

        $data[]=[
            'mes'       => 'TOTAL',
            'eje1'      => $ejercicio_1[$i],
            'eje2'      => $ejercicio_2[$i],
            'dif1_2'    => $dif1_2,
            'por1_2'    => $por1_2,
            'eje3'      => $ejercicio_3[$i],
            'dif1_3'    => $dif1_3,
            'por1_3'    => $por1_3,
            'eje4'      => $ejercicio_4[$i],
            'dif1_4'    => $dif1_4,
            'por1_4'    => $por1_4,
        ];

        return [
            'items' => $data,
            'ejercicios' => $ejercicios
        ];

    }

    private function load($area_id, $ejercicio, $fecha){

        $limite = $ejercicio.substr($fecha,4,6);

        $collection = DB::table('cobros')
                    ->select(DB::raw('MONTH(fecha) as mes, SUM(importe) AS importe'))
                        ->where('empresa_id', session('empresa')->id)
                        ->where('area_id', $area_id)
                        ->whereYear('fecha', $ejercicio)
                        ->whereDate('fecha', '<=', $limite)
                        ->groupBy('mes')
                        ->orderBy('mes')
                        ->get();

        for ($i=1;$i<=12;$i++)
            $valores[$i]=0;

        foreach ($collection as $row){
            $valores[$row->mes]= (float) $row->importe;
        }


        return $valores;

    }

    public function excel(Request $request){

        $data = $request->validate([
            'fecha'     => ['required','date'],
            'area_id'   => ['required','integer'],
        ]);

        $datos = $this->procesar($data);

        return Excel::download(new CobrosMesExport($datos), 'report.xlsx');

    }

}
