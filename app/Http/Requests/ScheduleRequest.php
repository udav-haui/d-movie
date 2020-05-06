<?php

namespace App\Http\Requests;

use App\FilmSchedule as Schedule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;

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
                'date_format:Y-m-d'
            ],
            'start_time' => [
                'required',
                'date_format:H:i'
            ]
        ];
        /**
         * Validate unique start date
         */
        $unique = Rule::unique(Schedule::class)->where(function ($query) {
            $query->where(Schedule::FILM, (int)$this->film_id)
                ->where(Schedule::SHOW, (int)$this->show_id);
        });
        if ($this->f) {
            $unique->ignore($this->f->getId());
        }

        $rules[Schedule::START_DATE][] = $unique;

        if ($this->f) {
            unset($rules['start_time']);
        }
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
            Schedule::START_DATE.'.unique' => __('The other :attribute has been exist. Please choose other value.'),
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

    /**
     * Prepare date format before validate to database
     *
     * @return MessageBag|void
     */
    protected function prepareForValidation()
    {
//        try {
//            $formattedDate = \Carbon\Carbon::make($this->start_date)->format('Y-m-d');
//        } catch (\Exception $e) {
//            $msgBag = new MessageBag();
//            $msgBag->add(
//                Schedule::START_DATE,
//                __('Please input a correct date format (dd-mm-yyyy)')
//            );
//
//            return $msgBag;
//        }

        if ($this->start_date) {
            $this->merge([
                Schedule::START_DATE => \Carbon\Carbon::make($this->start_date)->format('Y-m-d')
            ]);
        }
    }
}
