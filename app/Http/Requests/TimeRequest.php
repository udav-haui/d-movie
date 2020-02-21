<?php

namespace App\Http\Requests;

use App\Time;
use Illuminate\Foundation\Http\FormRequest;

class TimeRequest extends FormRequest
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
            Time::START_TIME => [
                'required',
                'date_format:H:i'
            ]
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            Time::START_TIME.'.required' => __('The :attribute field is required.'),
            Time::START_TIME.'.date_format' => __('Please input a correct time format (HH:ii)'),
        ];
    }

    public function attributes()
    {
        return [
            Time::START_TIME => __('Start Time')
        ];
    }
}
