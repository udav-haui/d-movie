<?php

namespace App\Http\Requests;

use App\Rules\UnsignedInteger;
use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
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
            'title' => 'required|max:255',
            'image' => 'required|image',
            'href' => 'required',
            'order' => [
                'required',
                'numeric',
                new UnsignedInteger
            ]
        ];

        /* If request is update, has slider id */
        if ($this->slider) {
            $rules['image'] = 'nullable|image';
        }

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
            'title.required' => __('You must input :attribute'),
            'title.max' => __('You can not input more than :max character'),
            'image.image' => __('Please input correct type of :attribute.'),
            'image.required' => __('Please select a file.'),
            'href.required' => __('You must input :attribute'),
            'order.required' => __('You must input :attribute'),
            'order.numeric' => __('The :attribute must be number.')
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
            'title' => __('Title'),
            'image' => __('Image'),
            'href' => __('Link'),
            'order' => __('Order')
        ];
    }
}
