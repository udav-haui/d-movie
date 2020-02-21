<?php

namespace App\Http\Requests;

use App\Combo;
use Illuminate\Foundation\Http\FormRequest;

class ComboRequest extends FormRequest
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
            Combo::PRICE => 'required|numeric',
            Combo::NAME => 'required|max:255',
            Combo::DESCRIPTION => 'max:255'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            Combo::NAME.'.required' => __('The [:attribute] is required'),
            Combo::PRICE.'.required' => __('The [:attribute] is required'),
            Combo::PRICE.'.numeric' => __('The :attribute must be number'),
            Combo::NAME.'.max' => __('You can not input more than :max character'),
            Combo::DESCRIPTION.'.max' => __('You can not input more than :max character'),
        ];
    }

    public function attributes()
    {
        return [
            Combo::NAME => __('Combo Name'),
            Combo::PRICE => __('Combo Price'),
            Combo::DESCRIPTION => __('Description')
        ];
    }
}
