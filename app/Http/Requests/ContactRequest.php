<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'cinema_id' => 'required',
            'contact_name' => 'required|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => [
                'required',
                'regex:/((\+84|84|0)[9|3])+([0-9]{8})\b/i'
            ],
            'contact_content' => 'required'
        ];

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'cinema_id.required' => __('The :attribute is required'),
            'contact_name.required' => __('The :attribute is required'),
            'contact_email.required' => __('The :attribute is required'),
            'contact_phone.required' => __('The :attribute is required'),
            'contact_content.required' => __('The :attribute is required'),
            'contact_phone.regex' => __('Please input a correct Vietnam :attribute'),
            'contact_name.max' => __('You can not input more than :max character'),
            'contact_email.max' => __('You can not input more than :max character'),
            'contact_email.email' => __('You must input a correct :attribute'),
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'cinema_id' => __('Cinema'),
            'contact_name' => __('Name'),
            'contact_email' => __('Email'),
            'contact_phone' => __('Phone'),
            'contact_content' => __('Content')
        ];
    }
}
