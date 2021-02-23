<?php

namespace App\Http\Requests\Citas;

use App\Rules\Citas\HoraRule;
use App\Rules\Citas\FestivoRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCitaRequest extends FormRequest
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
        $k = $this;

        $rules = [
            'area_id'           => ['required','integer'],
            'facultativo_id'    => ['required','integer'],
            'fecha'             => ['required','date', new FestivoRule()],
            'hora'              => ['required', new HoraRule($this->fecha, $this->facultativo_id)],
            'paciente_id'       => ['required','integer'],
            'tratamiento_id'    => ['required','integer'],
            'mutua_id'          => ['nullable','integer'],
            'importe'           => ['required','numeric'],
            'importe_ponderado' => ['required','numeric'],
            'bono'              => ['nullable','integer'],
            'estado_id'         => ['required','integer'],
            'notas'             => ['nullable','max:190'],
            'tune'              => ['boolean']        ];

        return $rules;
    }
}
