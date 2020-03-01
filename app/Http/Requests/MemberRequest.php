<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class MemberRequest extends FormRequest
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
            User::NAME => 'required|max:255|min:2',
            User::PHONE => [
                'required',
                'regex:/((\+84|84|0)[9|3])+([0-9]{8})\b/i'
            ],
            User::DOB => [
                'nullable',
                'date_format:d-m-Y'
            ],
            User::ADDRESS => 'nullable|max:255'
        ];
    }


    /**
     * Define message for validate request
     *
     * @return array
     */
    public function messages()
    {
        return [
            User::NAME.'.required' => __('You must input :attribute'),
            User::NAME.'.max' => __('You can not input more than :max character'),
            User::NAME.'.min' => __('You can not input less than :min character'),
            User::PHONE.'.regex' => __('Please input a correct Vietnam :attribute'),
            User::PHONE.'.required' => __('You must input :attribute'),
            User::DOB.'.required' => __('You must input :attribute'),
            User::DOB.'.date_format' => __('Please input a correct date format (dd-mm-yyyy)'),
            User::ADDRESS.'max' => __('You can not input more than :max character'),
        ];
    }

    /**
     * Define name of attribute
     *
     * @return array
     */
    public function attributes()
    {
        return [
            User::NAME => __('name'),
            User::PHONE => __('Phone'),
            User::DOB => __('Date of birth'),
            User::ADDRESS => __('Address')
        ];
    }
}
