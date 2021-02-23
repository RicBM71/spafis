<?php

namespace App\Http\Requests\Mto;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHorarioRequest extends FormRequest
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
            'fecha'             => ['required','date'],
            'inim_1' => ['required','date_format:H:i:s'],
            'finm_1' => ['required','date_format:H:i:s'],
            'init_1' => ['required','date_format:H:i:s'],
            'fint_1' => ['required','date_format:H:i:s'],
            'inim_2' => ['required','date_format:H:i:s'],
            'finm_2' => ['required','date_format:H:i:s'],
            'init_2' => ['required','date_format:H:i:s'],
            'fint_2' => ['required','date_format:H:i:s'],
            'inim_3' => ['required','date_format:H:i:s'],
            'finm_3' => ['required','date_format:H:i:s'],
            'init_3' => ['required','date_format:H:i:s'],
            'fint_3' => ['required','date_format:H:i:s'],
            'inim_4' => ['required','date_format:H:i:s'],
            'finm_4' => ['required','date_format:H:i:s'],
            'init_4' => ['required','date_format:H:i:s'],
            'fint_4' => ['required','date_format:H:i:s'],
            'inim_5' => ['required','date_format:H:i:s'],
            'finm_5' => ['required','date_format:H:i:s'],
            'init_5' => ['required','date_format:H:i:s'],
            'fint_5' => ['required','date_format:H:i:s'],
            // 'inim_6' => ['required','date_format:H:i:s'],
            // 'finm_6' => ['required','date_format:H:i:s'],
            // 'init_6' => ['required','date_format:H:i:s'],
            // 'fint_6' => ['required','date_format:H:i:s'],
            // 'inim_0' => ['required','date_format:H:i:s'],
            // 'finm_0' => ['required','date_format:H:i:s'],
            // 'init_0' => ['required','date_format:H:i:s'],
            // 'fint_0' => ['required','date_format:H:i:s'],
        ];
    }
}
