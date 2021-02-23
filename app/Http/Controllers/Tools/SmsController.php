<?php

namespace App\Http\Controllers\Tools;

use Carbon\Carbon;
use App\Models\Cita;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SmsController extends Controller
{

    //protected $api_key = 'b7a5a919574346a2b67a3a5cb2c18bad';
    protected $fake = '1';

    public function index($dt){

        //$data['fecha']="2020-12-10";


        $citas = Cita::join('pacientes','pacientes.id','=','paciente_id')
                        ->where('fecha', $dt)
                        ->where('estado_id', '<>', 4)
                        ->where('notificar', 'S')
                        ->whereNull('envio_sms')
                        ->select('citas.id','nombre', 'telefonom', 'fecha', 'hora','apellidos')
                        ->orderBy('hora')
                        ->get();

        if (request()->wantsJson()){
            return $citas;
        }

    }

    public function send(Request $request){

        $data = $request->validate([
            'citas' => 'required'
        ]);


        $citas = collect($data['citas']);

        $sms = 0;
        $messages = array();
        foreach ($citas as $cita){

            $msg = $this->setSMS((object)$cita);

            if ($msg == false)
                continue;

            array_push($messages, $msg);
            //$messages[] = $msg;

            $sms++;

        }

        //return;

        if ($sms == 0)
            return abort(404, 'No hay sms para enviar');

        // $messages[] = [
        //     "from"   => $this->from,
        //     "to"     => "34666483094",
        //     "text"   => 'Check envío programado SMS, ok!',
        //     "send_at"=>  date('Y-m-d').' 18:00:00'
        // ];

        //return $messages;

       // dd($messages);

        $sms_response = $this->setSendRequestSMS($messages);

        if ($sms_response->status == 'ok')
            $this->update($sms_response->result);

        return response($sms, 200);

    }

    private function setSendRequestSMS($messages){

        $request = [
            'api_key'   => session('empresa')->sms_api,
            'concat'    => '1',
            'fake'      => $this->fake,
            'encoding'  => 'UCS2',
            'messages'  => $messages

        ];

        $request = (json_encode($request));

        $headers = array('Content-Type: application/json');

        $ch = curl_init('https://api.gateway360.com/api/3.0/sms/send');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);

        $result = curl_exec($ch);

        $sms_response = json_decode($result);

        if (curl_errno($ch) != 0 ){
            return abort(411, curl_errno($ch));
            //die("curl error: ".curl_errno($ch));
        }

        return $sms_response;
    }

    private function setSMS($cita, $programado = true){

        if (strlen($cita->telefonom) != 9) return false;

        $msg = 'Hola %n, tienes cita el %f. Si no puedes acudir te agradeceríamos que nos lo comunicases con antelación. Gracias.';

        $dt = Carbon::parse($cita->fecha.' '.$cita->hora);
        $fecha = $dt->isoFormat('dddd, D [de] MMMM [a las] H:mm');

        $msg = str_replace('%n', mb_convert_case(trim($cita->nombre),MB_CASE_TITLE), $msg);
        $msg = str_replace('%f', $fecha, $msg);

        $hora_envio = $programado ? ($dt->hour < 15) ?  $dt->subDay()->format('Y-m-d').' '.session('empresa')->sms_am : $dt->format('Y-m-d').' '.session('empresa')->sms_pm
                                    : null;

        return [
            "from"   => session('empresa')->sms_sender,
            "to"     => "34".$cita->telefonom,
            "text"   => $msg,
            "custom" => $cita->id,
            "send_at"=> $hora_envio
        ];

    }

    private function update($data){

        $now = Carbon::now();

        foreach ($data as $sms){

            if ($sms->status != 'ok')
                $errores = true;

            Cita::where('id', $sms->custom)->update(
                [
                    'sms_id'    => $sms->sms_id,
                    'envio_sms' => $now
                ]
            );
        }

    }

    public function sendone(Request $request, $cita_id){

        $cita = Cita::join('pacientes','pacientes.id','=','paciente_id')
                        ->select('citas.id','nombre', 'telefonom', 'fecha', 'hora')
                        ->findOrFail($cita_id);

        $messages = array();

        $msg = $this->setSMS($cita, false);

        if ($msg == false)
            return abort(404, 'No hay sms para enviar');

        array_push($messages, $msg);

        $sms_response = $this->setSendRequestSMS($messages);

        if ($sms_response->status == 'ok')
            $this->update($sms_response->result);

        return response('ok', 200);

    }

    public function cancelSMS(Request $request, Cita $cita){

        $ids[] = [
            "id_sms" => $cita->sms_id
        ];

        $request = [
            'api_key'   => session('empresa')->sms_api,
            'ids'       => $ids
        ];

        $request = (json_encode($request));

        $headers = array('Content-Type: application/json');

        $ch = curl_init('https://api.gateway360.com/api/3.0/sms/cancel');

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);

        $result = curl_exec($ch);

        if (curl_errno($ch) != 0 ){
            return abort(411, curl_errno($ch));
            //die("curl error: ".curl_errno($ch));
        }

        $sms_response = json_decode($result);

        if ($sms_response->status == 'ok')
            $cita->update([
                'sms_id'    => null,
                'envio_sms' => null
            ]);

        //http://localhost:3000/tools/sms/smspubli

        return response('ok', 200);

    }
}
