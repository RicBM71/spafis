<?php

namespace App\Http\Requests\Mto;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFacultativoRequest extends FormRequest
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
        return [
            'nombre'            => ['required', 'string', 'max:30'],
            'apellidos'         => ['required', 'string', 'max:45'],
            'direccion'         => ['nullable','string', 'max:50'],
            'cpostal'           => ['nullable','string', 'max:10'],
            'poblacion'         => ['nullable','string', 'max:40'],
            'provincia'         => ['nullable','string', 'max:30'],
            'telefono1'         => ['nullable','string', 'max:15'],
            'telefono2'         => ['nullable','string', 'max:15'],
            'telefonom'         => ['nullable','string', 'max:15'],
            'email'             => ['nullable','email', 'max:50'],
            'fecha_nacimiento'  => ['nullable','date'],
            'numero_ss'         => ['nullable','max:30'],
            'colegiado'         => ['nullable','integer'],
            'color'             => ['nullable'],
            'alias'             => ['nullable', 'max:20'],
            'iban'              => ['nullable','iban'],
            'orden'             => ['nullable','integer'],
            'categoria_id'      => ['required','integer'],
            'grupo_id'          => ['required','integer'],
            'objetivo'          => ['required','integer'],
        ];

    }
}
