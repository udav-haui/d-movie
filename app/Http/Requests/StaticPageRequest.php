<?php

namespace App\Http\Requests;

use App\StaticPage;
use Illuminate\Foundation\Http\FormRequest;

class StaticPageRequest extends FormRequest
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
            StaticPage::NAME => [
                'required',
                'max:255'
            ],
            StaticPage::SLUG => 'required|max:255|unique:static_pages,slug'
        ];

        if ($this->static_page) {
            $rules[StaticPage::SLUG] = 'required|max:255|unique:static_pages,slug,' . $this->static_page->getId();
        }
        return $rules;
    }

    public function messages()
    {
        return [
            StaticPage::NAME.'.required' => __('The [:attribute] is required'),
            StaticPage::SLUG.'.required' => __('The [:attribute] is required'),
            StaticPage::NAME.'.max' => __('You can not input more than :max character'),
            StaticPage::SLUG.'.max' => __('You can not input more than :max character'),
            StaticPage::SLUG.'.unique' => __('The [:attribute] is exist')
        ];
    }

    public function attributes()
    {
        return [
            StaticPage::SLUG => __('page slug'),
            StaticPage::NAME => __('page name')
        ];
    }
}
