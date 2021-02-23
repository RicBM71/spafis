<?php

namespace App\Http\Controllers\Mto;

use App\Models\Adjunto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AdjuntosController extends Controller
{


    public function show(Adjunto $adjunto){


        $ficheroPath = str_replace('/storage', '', $adjunto->path);

        $ext = explode('.',$ficheroPath);
        $ext = array_pop($ext);

        $filename = 'docu'.date('Ymd').'.'.$ext;

        $img = ['pdf','jpg','jpeg','png','gif','doc','docx'];

        $data['leido'] = true;
        $data['username'] = session('username');
        $adjunto->update($data);

        if (in_array($ext, $img)) // este abre el fichero en el navegador
            return response()->file(storage_path('/app/' . $ficheroPath));
        else
            return Storage::download($ficheroPath, $filename);

        //return response()->download(storage_path('/app/' . $ficheroPath,$filename));

    }


    public function update(Request $request, Adjunto $adjunto)
    {

        $data = $request->validate([
            'descripcion' => ['nullable', 'string', 'max:250'],
            'leido'       => ['boolean']
        ]);

        $data['username'] = session('username');


        $adjunto->update($data);

        if (request()->wantsJson())
            return response('ok');
    }


    public function upload(Request $request, $paciente_id)
    {

        $data = $request->validate([
            'adjunto' => ['required'],
        ]);

        $disco = "docs/".session('empresa')->disco;

        //$files = request()->file('files')->store($path,'local');

        //$filename = 'hc'.$paciente_id.date('ymdhhmmss');


        $documento = request()->file('adjunto')->store($disco);

        $url = Storage::url($documento);

        $data['paciente_id']=$paciente_id;
        $data['descripcion']='n/d';
        $data['path']=$url;
        $data['username'] = $request->user()->username;

        $reg = Adjunto::create($data);

        if (request()->wantsJson())
            return ['adjuntos'=> Adjunto::where('paciente_id', $paciente_id)->get()];
    }

    public function destroy(Adjunto $adjunto)
    {

        $adjunto->delete();

        if (request()->wantsJson()){
            return response('ok',200);
        }
    }


}
