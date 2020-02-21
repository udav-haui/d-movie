<?php

namespace App\Http\Requests;

use App\FilmSchedule as Schedule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ScheduleRequest
 *
 * @package App\Http\Requests
 */
class ScheduleRequest extends FormRequest
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
        $rules  = [
            Schedule::FILM => 'required',
            Schedule::SHOW => 'required',
            Schedule::START_DATE => [
                'required',
                'date_format:d-m-Y'
            ],
            'start_time' => [
                'required',
                'date_format:H:i'
            ]
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            Schedule::FILM.'.required' => __('The :attribute field is required.'),
            Schedule::SHOW.'.required' => __('The :attribute field is required.'),
            'start_time.required' => __('The :attribute field is required.'),
            'start_time.date_format' => __('Please input a correct time format (HH:ii)'),
            Schedule::START_DATE.'.required' => __('The :attribute field is required.'),
            Schedule::START_DATE.'.date_format' => __('Please input a correct date format (dd-mm-yyyy)')
        ];
    }

    public function attributes()
    {
        return [
            Schedule::SHOW => __('Show Name'),
            Schedule::FILM => __('Film Name'),
            Schedule::START_DATE => __('Start Date'),
            'start_time' => __('Start Time')
        ];
    }
}
