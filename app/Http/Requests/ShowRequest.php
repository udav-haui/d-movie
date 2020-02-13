<?php

namespace App\Http\Requests;

use App\Repositories\Interfaces\ShowInterface as Show;
use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
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
            Show::NAME => [
                'required',
                'max:255',
                'unique:shows'
            ]
        ];

        if ($this->show) {
            $rules[Show::NAME] = [
                'required',
                'max:255',
                'unique:shows,' . Show::NAME . ',' . $this->show->getId()
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            Show::NAME.'.required' => __('You must input :attribute'),
            Show::NAME.'.max' => __('You can not input more than :max character'),
            Show::NAME.'.unique' => __('The :attribute has been exist.'),
        ];
    }

    public function attributes()
    {
        return [
            Show::NAME => __('Show Name'),
        ];
    }
}
