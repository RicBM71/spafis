<?php

namespace App\Http\Controllers\Tools;

use App\Models\Paciente;
use Illuminate\Http\Request;
use App\Exports\ContactsExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class PacientesExportController extends Controller
{

    public function export(){

        if (!esAdmin())
            return abort(403, 'No autorizado! - admin');

        $collection = DB::table('pacientes')
                                ->join('citas','citas.paciente_id','pacientes.id')
                                ->where('telefonom','>','')
                                ->whereNull('fecha_baja')
                                ->where('exportar', true)
                                ->whereYear('fecha', '>=', date('Y') - 5)
                                ->select('nombre','apellidos','telefonom')
                                ->distinct()
                                ->get();

        return Excel::download(new ContactsExport($collection), 'Contacts.csv');

    }
}
