<?php

namespace App\Http\Requests\Mto;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePacbonoRequest extends FormRequest
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
            'fecha'         => ['required','date'],
            'bono'          => ['required','integer'],
            'paciente_id'   => ['required','integer'],
            'tratamiento_id'=> ['required','integer'],
            'importe'       => ['required','numeric'],
            'sesiones'      => ['required','integer'],
            'caducidad'     => ['required','integer'],
            'texto'         => ['nullable'],
            'caducado'      => ['boolean','required'],
        ];
    }
}
