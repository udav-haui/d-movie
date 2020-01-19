<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
            'role_name' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'role_name.required' => __('You must input :attribute')
        ];
    }

    public function attributes()
    {
        return [
            'role_name' => __('Role name')
        ];
    }
}
