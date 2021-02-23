<?php

namespace App\Http\Requests\Mto;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTratamientoRequest extends FormRequest
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
            'nombre'           => ['required','max:50'],
            'nombre_reducido'  => ['required','max:5'],
            'nombre_web'       => ['required','max:50'],
            'importe'          => ['required','numeric'],
            'importe_reducido' => ['required','numeric'],
            'precio_coste'     => ['required','numeric'],
            'duracion_manual'  => ['required','integer'],
            'duracion_aparatos'=> ['required','integer'],
            'edad'             => ['required','integer','max:99'],
            'tpv'              => ['boolean','required'],
            'inventario'       => ['boolean','required'],
            'activo'           => ['boolean','required'],
            'iva_id'           => ['required','integer'],
        ];
    }
}
