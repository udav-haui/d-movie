<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            User::USERNAME => [
                'required',
                'unique:users,username',
                'max:255',
                'min:4',
                'regex:/(^([\.\_0-9a-zA-Z]+)(\d+)?$)/u'
            ],
            User::NAME => 'nullable|max:255|min:2',
            User::EMAIL => 'required|max:255|email|unique:users,email',
            User::PHONE => [
                'nullable',
                'regex:/((\+84|84|0)[9|3])+([0-9]{8})\b/i'
            ],
            User::DOB => [
                'nullable',
                'date_format:d-m-Y'
            ],
            User::PASSWORD => [
                'required',
                'min:6'
            ]
        ];

        if ($this->customer) {
            $rules[User::USERNAME] = [
                'required',
                'unique:users,username,' . $this->customer->getId(),
                'max:255',
                'min:4',
                'regex:/(^([\.\_0-9a-zA-Z]+)(\d+)?$)/u'
            ];
            $rules[User::EMAIL] = 'required|max:255|email|unique:users,email,' . $this->customer->getId();
            $rules[User::PASSWORD] = 'nullable|min:6';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            User::USERNAME.'.unique' => __('This :attribute has been taken'),
            User::USERNAME.'.max' => __('You can not input more than :max character'),
            User::USERNAME.'.min' => __('You can not input less than :min character'),
            User::USERNAME.'.regex' => __('Please input a correct :attribute. Only 0-9. a-z. A-Z _ .'),
            User::USERNAME.'.required' => __('You must input :attribute'),
            User::NAME.'.required' => __('You must input :attribute'),
            User::NAME.'.max' => __('You can not input more than :max character'),
            User::NAME.'.min' => __('You can not input less than :min character'),
            User::EMAIL.'.required' => __('You must input :attribute'),
            User::EMAIL.'.max' => __('You can not input more than :max character'),
            User::EMAIL.'.email' => __('You must input a correct :attribute'),
            User::EMAIL.'.unique' => __('This :attribute has been taken'),
            User::PHONE.'.regex' => __('Please input a correct Vietnam :attribute'),
            User::PHONE.'.required' => __('You must input :attribute'),
            User::DOB.'.required' => __('You must input :attribute'),
            User::DOB.'.date_format' => __('Please input a correct date format (dd-mm-yyyy)'),
            User::PASSWORD.'.required' => __('You must input :attribute'),
            User::PASSWORD.'.min' => __(':attribute need at least :min character'),
        ];
    }

    public function attributes()
    {
        return [
            User::USERNAME => __('username'),
            User::NAME => __('name'),
            User::EMAIL => __('Email'),
            User::PHONE => __('Phone'),
            User::DOB => __('Date of Birth'),
            User::PASSWORD => __('Password')
        ];
    }
}
