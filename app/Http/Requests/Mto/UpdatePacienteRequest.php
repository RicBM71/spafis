<?php

namespace App\Http\Requests\Mto;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePacienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $data = [

            'nombre'            => ['required','string', 'max:30'],
            'apellidos'         => ['required','string', 'max:45'],
            'direccion'         => ['nullable','string', 'max:50'],
            'cpostal'           => ['nullable','string', 'max:10'],
            'poblacion'         => ['nullable','string', 'max:40'],
            'provincia'         => ['nullable','string', 'max:30'],
            'telefono1'         => ['nullable','string', 'max:15'],
            'telefono2'         => ['nullable','string', 'max:15'],
            'telefonom'         => ['nullable','string', 'max:15'],
            'email'             => ['nullable','email', 'max:50'],
            'cif'               => ['nullable','string', 'max:15'],
            'fecha_nacimiento'  => ['required','date'],
            'fecha_baja'        => ['nullable','date'],
            'notificar'         => ['nullable','string'],'max:1',
            'texto_tf2'         => ['nullable','string','max:50'],
            'email'             => ['nullable','string'],
            'cif'               => ['nullable','string'],
            'sexo'              => ['nullable','string'],
            'descuento'         => ['nullable','numeric'],
            'porcentual'        => ['nullable','boolean'],
            'profesion'         => ['nullable','string'],
            'tarifa_reducidad'  => ['nullable','boolean'],
            'mutua_id'          => ['nullable','integer'],
            'exportar'          => ['nullable','boolean'],
            'riesgo'            => ['nullable','numeric'],
            'notas1'            => ['nullable','string'],
            'notas2'            => ['nullable','string'],
            'medio_id'          => ['nullable','integer'],
            'recomendado_id'    => ['nullable','integer'],
            'ant1'              => ['nullable','string'],
            'ant2'              => ['nullable','string'],
            'ant3'              => ['nullable','string'],
            'ant4'              => ['nullable','string'],
            'ant5'              => ['nullable','string'],
            'ant6'              => ['nullable','string'],
            'antobs'            => ['nullable','string'],
            'embarazada'        => ['nullable','boolean'],
            'peso'              => ['nullable','string'],
            'altura'            => ['nullable','string'],
            'lortad_evo'        => ['nullable','boolean'],
            'lortad_fide'       => ['nullable','boolean'],
            'notas_adm'           => ['nullable','string'],
            'espera'            => ['nullable','boolean'],
            'factura_auto'      => ['nullable','boolean'],
            'notificar'         => ['string', 'max:1']
        ];

        return $data;
    }
}
