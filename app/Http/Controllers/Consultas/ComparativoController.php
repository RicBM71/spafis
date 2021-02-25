<?php

namespace App\Http\Controllers\Consultas;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\Paciente;
use App\Models\Tratamiento;
use Illuminate\Http\Request;
use App\Exports\ComparativoExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ComparativoController extends Controller
{

    protected $p1;
    protected $p2;

    public function index(){


        if (request()->wantsJson())
            return [
                'areas'  => Area::selAreas(),
            ];

    }

    public function submit(Request $request){

        $data = $request->validate([
            'fechad'     => ['required','date'],
            'fechah'     => ['required','date'],
            'area_id'   => ['required','integer'],
        ]);

        return $this->procesar($data);
    }

    private function procesar($data){

        $ejercicio = getEjercicio($data['fechad']);

        $this->p1 = str_replace($ejercicio,$ejercicio - 1, $data['fechad']);
        $this->p2 = str_replace($ejercicio,$ejercicio - 1, $data['fechah']);

        // cargar x tratamiento
        $collection = $this->load($data);

        $items = array();
        $totales=array(0,0,0,0);
        foreach ($collection as $row){

            $dif_abs = $row->p1 - $row->p2;
            $dif_rel = $row->p2 != 0 ? round($dif_abs / $row->p2 * 100, 2): 0;

            $idif_abs = $row->i1 - $row->i2;
            $idif_rel = $row->i2 != 0 ? round($idif_abs / $row->i2 * 100, 2): 0;

            $items[]= [
                'tratamiento' => Tratamiento::find($row->id)->nombre,
                'p1'          => $row->p1,
                'p2'          => $row->p2,
                'difabs'      => $dif_abs,
                'difrel'      => $dif_rel,
                'i1'          => $row->i1,
                'i2'          => $row->i2,
                'idifabs'     => $idif_abs,
                'idifrel'     => $idif_rel,
            ];

            $totales[0]+=$row->p1;
            $totales[1]+=$row->p2;
            $totales[2]+=$row->i1;
            $totales[3]+=$row->i2;

        }

        $dif_abs = $totales[0] - $totales[1];
        $dif_rel = $totales[1] != 0 ? round($dif_abs / $totales[1] * 100, 2): 0;

        $idif_abs = $totales[2] - $totales[3];
        $idif_rel = $totales[3] != 0 ? round($idif_abs / $totales[3] * 100, 2): 0;

        $items[]= [
            'tratamiento' => 'TOTAL',
            'p1'          => $totales[0],
            'p2'          => $totales[1],
            'difabs'      => $dif_abs,
            'difrel'      => $dif_rel,
            'i1'          => $totales[2],
            'i2'          => $totales[3],
            'idifabs'     => $idif_abs,
            'idifrel'     => $idif_rel,
        ];

        // nuevos pacientes

        $nuevos = [
            'act' => $this->nuevosPacientesCreados($data['fechad'], $data['fechah']),
            'ant' => $this->nuevosPacientesCreados($this->p1, $this->p2),
            'uni_act' => $this->unicos($data['area_id'], $data['fechad'], $data['fechah']),
            'uni_ant' => $this->unicos($data['area_id'], $this->p1, $this->p2),
        ];

        $cobros = $this->cobros($data['area_id'], $data['fechad'], $data['fechah']);
        $total = $cobros->sum('importe');


        return [
            'items'     => $items,
            'pacientes' => $nuevos,
            'trafis'    => $this->tratamientosFacultativo($data['area_id'], $data['fechad'], $data['fechah']),
            'cobros'    => $cobros,
            'total_cobrado'    => $total,
            'pacientes_medio' => $this->medioPacientes($data['fechad'], $data['fechah'])
        ];

    }

    private function nuevosPacientesCreados($d, $h){

        return Paciente::whereDate('created_at', '>=', $d)
                       ->whereDate('created_at', '<=', $h)
                       ->get()
                       ->count();
    }

    private function medioPacientes($d, $h){

        return DB::table('pacientes')
                    ->select(DB::raw('medios.nombre, COUNT(*) AS pacientes'))
                        ->join('medios','medios.id','=','medio_id')
                        ->whereDate('pacientes.created_at', '>=', $d)
                        ->whereDate('pacientes.created_at', '<=', $h)
                        ->groupBy('nombre')
                        ->orderBy('pacientes','desc')
                        ->get();
    }

    private function unicos($area_id, $d, $h){

        return DB::table('citas')
                    ->select(DB::raw('DISTINCT paciente_id'))
                        ->where('area_id', $area_id)
                        ->whereDate('fecha', '>=', $d)
                        ->whereDate('fecha', '<=', $h)
                        ->where('estado_id', '<>', 4)
                        ->get()
                        ->count();

    }

    private function tratamientosFacultativo($area_id, $d, $h){

        return DB::table('citas')
                    ->select(DB::raw('facultativos.alias, IFNULL(SUM(importe_ponderado),0) AS importe, COUNT(*) AS sesiones'))
                        ->join('facultativos','facultativos.id','=','facultativo_id')
                        ->where('area_id', $area_id)
                        ->whereDate('fecha', '>=', $d)
                        ->whereDate('fecha', '<=', $h)
                        ->where('estado_id', '<>', 4)
                        ->groupBy('alias')
                        ->orderBy('sesiones','desc')
                        ->get();

    }

    private function load($data){


        $s1 = '(SELECT COUNT(*) FROM citas WHERE empresa_id = '.session('empresa')->id.' AND area_id = '.$data['area_id'].' AND fecha >= "'.$data['fechad'].'" AND fecha <= "'.$data['fechah'].'" AND tratamiento_id = tratamientos.id  AND estado_id <> 4)';
        $s2 = '(SELECT COUNT(*) FROM citas WHERE empresa_id = '.session('empresa')->id.' AND area_id = '.$data['area_id'].' AND fecha >= "'.$this->p1.'" AND fecha <= "'.$this->p2.'" AND tratamiento_id = tratamientos.id  AND estado_id <> 4)';

        $i1 = str_replace('COUNT(*)', 'IFNULL(SUM(importe_ponderado),0)', $s1);
        $i2 = str_replace('COUNT(*)', 'IFNULL(SUM(importe_ponderado),0)', $s2);

        $collection = DB::table('tratamientos')
                    ->select(DB::raw('tratamientos.id,'.$s1.' AS p1,'.$s2.' AS p2, '.$i1.' AS i1,'.$i2.' AS i2'))
                        ->havingRaw('p1 > 0 OR p2 > 0')
                        ->orderBy('tratamientos.id')
                        ->get();

        return $collection;

    }

    private function cobros($area_id, $d, $h){

        return DB::table('cobros')
                    ->select(DB::raw('fpagos.nombre, IFNULL(SUM(importe),0) AS importe'))
                        ->join('fpagos','fpagos.id','=','fpago_id')
                        ->where('area_id', $area_id)
                        ->whereDate('fecha', '>=', $d)
                        ->whereDate('fecha', '<=', $h)
                        ->groupBy('nombre')
                        ->orderBy('importe','desc')
                        ->get();

    }

    public function excel(Request $request){

        $data = $request->validate([
            'fechad'     => ['required','date'],
            'fechah'     => ['required','date'],
            'area_id'   => ['required','integer'],
        ]);

        $datos = $this->procesar($data);

        return Excel::download(new ComparativoExport($datos), 'report.xlsx');

    }

}
