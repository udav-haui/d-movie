<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Cinema;

/**
 * Class CinemaRequest
 *
 * @package App\Http\Requests
 */
class CinemaRequest extends FormRequest
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
            Cinema::NAME => 'required|max:255',
            Cinema::ADDRESS => 'required|max:255',
            Cinema::PROVINCE => 'required|max:255',
            Cinema::PHONE => 'required'
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
            Cinema::NAME.'.required' => __('You must input :attribute'),
            Cinema::NAME.'.max' => __('You can not input more than :max character'),
            Cinema::ADDRESS.'.required' => __('You must input :attribute'),
            Cinema::ADDRESS.'.max' => __('You can not input more than :max character'),
            Cinema::PROVINCE.'.required' => __('You must input :attribute'),
            Cinema::PROVINCE.'.max' => __('You can not input more than :max character'),
            Cinema::PHONE.'.required' => __('You must input :attribute')
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
            Cinema::NAME => __('Cinema Name'),
            Cinema::ADDRESS => __('Address'),
            Cinema::PROVINCE => __('Province'),
            Cinema::PHONE => __('Phone')
        ];
    }
}
