<?php

namespace App\Http\Requests;

use App\Film;
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
            Film::TITLE => [
                'required',
                'max:255'
            ],
            Film::POSTER => [
                'required',
                'image'
            ],
            Film::DIRECTOR => 'required|max:255',
            Film::CAST => 'required|max:255',
            Film::GENRE => 'required|max:255',
            Film::RUNNING_TIME => [
                'required',
                'numeric',
                new UnsignedInteger
            ],
            FilM::LANGUAGE => 'nullable|max:255',
            Film::RELEASE_DATE => [
                'nullable',
                'date_format:d-m-Y'
            ],
            Film::MARK => [
                'required'
            ]
        ];

        if ($this->film) {
            $rules[Film::POSTER] = 'nullable|image';
        }

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
            'title.required' => __('You must input :attribute'),
            'title.max' => __('You can not input more than :max character'),
            'poster.image' => __('Please input correct type of :attribute.'),
            'poster.required' => __('Please select a file.'),
            'director.required' => __('You must input :attribute'),
            'director.max' => __('You can not input more than :max character'),
            'cast.required' => __('You must input :attribute'),
            'cast.max' => __('You can not input more than :max character'),
            'genre.required' => __('You must input :attribute'),
            'genre.max' => __('You can not input more than :max character'),
            'href.required' => __('You must input :attribute'),
            'running_time.required' => __('You must input :attribute'),
            'running_time.numeric' => __('The :attribute must be number.'),
            'language.max' => __('You can not input more than :max character'),
            'mark.required' => __('You must input :attribute'),
            Film::RELEASE_DATE.'.date_format' => __('Please input a correct date format (dd-mm-yyyy)')
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'title' => __('Film Title'),
            'poster' => __('Poster'),
            'director' => __('Director'),
            'cast' => __('Cast'),
            'genre' => __('Genre'),
            'running_time' => __('Running Time'),
            'language' => __('Language'),
            'release_date' => __('Release Date'),
            'mark' => __('Mark')
        ];
    }
}
