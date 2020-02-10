<?php

namespace App\Http\Requests;

use App\Rules\UnsignedInteger;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilmRequest extends FormRequest
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
        $rules = [
            'title' => [
                'required',
                'max:255'
            ],
            'poster' => [
                'required',
                'image'
            ],
            'director' => 'required|max:255',
            'cast' => 'required|max:255',
            'genre' => 'required|max:255',
            'running_time' => [
                'required',
                'numeric',
                new UnsignedInteger
            ],
            'language' => 'nullable|max:255',
            'release_date' => [
                'nullable',
                'date_format:d/m/Y'
            ],
            'mark' => [
                'required',
                Rule::in(['p', 'c13', 'c16', 'c18'])
            ]
        ];

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [

        ];
    }
}
