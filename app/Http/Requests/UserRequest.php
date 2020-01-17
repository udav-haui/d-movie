<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'username' => [
                'unique:users,username,' . $this->user->id,
                'max:255',
                'min:4',
                'regex:/(^([\.\_0-9a-zA-Z]+)(\d+)?$)/u'
            ],
            'name' => 'required|max:255|min:2',
            'email' => 'required|max:255|email|unique:users,email,' . $this->user->id,
            'phone' => [
                'required',
                'regex:/((\+84|84|0)[9|3])+([0-9]{8})\b/i'
            ],
            'dob' => [
                'required',
                'date_format:d/m/Y'
            ]
        ];
    }

    public function messages()
    {
        return [
            'username.unique' => __('This :attribute has been taken'),
            'username.max' => __('You can not input more than :max character'),
            'username.min' => __('You can not input less than :min character'),
            'username.regex' => __('Please input a correct :attribute. Only 0-9. a-z. A-Z _ .'),
            'name.required' => __('You must input :attribute'),
            'name.max' => __('You can not input more than :max character'),
            'name.min' => __('You can not input less than :min character'),
            'email.required' => __('You must input :attribute'),
            'email.max' => __('You can not input more than :max charactViệt Namer'),
            'email.email' => __('You must input a correct :attribute'),
            'email.unique' => __('This :attribute has been taken'),
            'phone.regex' => __('Please input a correct Vietnam :attribute'),
            'phone.required' => __('You must input :attribute'),
            'dob.required' => __('You must input :attribute'),
            'dob.date_format' => __('Please input a correct date format (dd/mm/yyyy)')
        ];
    }

    public function attributes()
    {
        return [
            'username' => __('username'),
            'name' => __('name'),
            'email' => __('Email'),
            'phone' => __('Phone'),
            'dob' => __('Date of birth')
        ];
    }
}
