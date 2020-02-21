<?php

namespace App\Http\Requests;

use App\Seat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SeatRequest extends FormRequest
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
            Seat::SHOW => 'required',
            Seat::ROW => [
                'required',
                'max:2'
            ]
        ];

        if (!$this->quick_make) {
            $rules[Seat::NUMBER] = [
                Rule::unique('seats')->where(function ($query) {
                    $query->where(Seat::SHOW, $this->show_id)->where(Seat::ROW, $this->row);
                })
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            Seat::NUMBER.'.unique' => __('The :attribute : :num has already been taken.', [
                'num' => $this->row.$this->number
            ])
        ];
    }

    public function attributes()
    {
        return [
            Seat::NUMBER => __('Seat number')
        ];
    }
}
