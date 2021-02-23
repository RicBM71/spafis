<?php

namespace App\Http\Requests\Mto;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBonoRequest extends FormRequest
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
            'nombre'         => ['required','max:50'],
            'tratamiento_id' => ['required','integer'],
            'importe'        => ['required','numeric'],
            'sesiones'       => ['required','integer'],
            'caducidad'      => ['required','integer'],
        ];
    }
}
