<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEmpresas extends FormRequest
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
            'nombre'        => ['required', 'string', 'max:50'],
            'razon'         => ['nullable','string', 'max:50'],
            'poblacion'     => ['nullable','string', 'max:50'],
            'direccion'     => ['nullable','string', 'max:50'],
            'cpostal'       => ['nullable','string', 'max:20'],
            'provincia'     => ['nullable','string', 'max:50'],
            'telefono1'     => ['nullable','string', 'max:20'],
            'telefono2'     => ['nullable','string', 'max:20'],
            'cif'           => ['nullable','string', 'max:20'],
            'contacto'      => ['nullable','string', 'max:30'],
            'email'         => ['nullable','email', 'max:50'],
            'web'           => ['nullable','string', 'max:50'],
            'txtpie1'       => ['nullable','string', 'max:150'],
            'txtpie2'       => ['nullable','string', 'max:150'],
            'flags'         => ['nullable','string', 'max:20'],
            'sigla'         => ['nullable','string', 'max:10'],
            'titulo'        => ['required','string', 'max:20'],
            'certificado'   => ['nullable','string', 'max:20'],
            'passwd_cer'    => ['nullable','string'],
            'ult_bono'      => ['required','integer'],
            'sms_usr'       => ['nullable','string', 'max:30'],
            'sms_password'  => ['nullable','string', 'max:30'],
            'sms_api'  => ['nullable','string', 'max:30'],
            'sms_sender'    => ['nullable','string', 'max:30'],
            'sms_pais'      => ['nullable','string', 'max:3'],
            'sms_zona'      => ['nullable','string', 'max:30'],
            'sms_am'        => ['nullable','dateformat:H:i:s'],
            'sms_pm'        => ['nullable','dateformat:H:i:s'],
            'ccc_ss'        => ['nullable','string', 'max:30'],
            'tpv'           => ['boolean'],
        ];

        // if ($this->filled('id'))
        //     $data['cif'] = ['nullable','string', 'max:20', Rule::unique('empresas')->ignore($this->id)];

        return $data;
    }
}
