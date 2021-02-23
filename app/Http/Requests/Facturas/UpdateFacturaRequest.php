<?php

namespace App\Http\Requests\Facturas;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFacturaRequest extends FormRequest
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
            'razon'             => ['required', 'string', 'max:30'],
            'direccion'         => ['required','string', 'max:50'],
            'cpostal'           => ['nullable', 'max:5'],
            'poblacion'         => ['required','string', 'max:40'],
            'provincia'         => ['required','string', 'max:30'],
            'cif'               => ['required','string', 'max:15'],
            'notas'             => ['nullable','string', 'max:190'],
            'cuenta_id'         => ['nullable','integer'],
            'fpago_id'          => ['required','integer'],
        ];
    }
}
