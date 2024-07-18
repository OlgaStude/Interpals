<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class postRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'text' => 'required| max: 7000',
            'lang' => 'required'
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'Пожалуйста, заполните это поле',
            '*.max' => 'Вы привысили лимит символов'
        ];
    }
}
