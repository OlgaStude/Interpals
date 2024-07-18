<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class commentRequest extends FormRequest
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
            'text' => 'required | max: 500'
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'Пожалуйста, заполните это поле',
            '*.max' => 'максимальное колличество символов было превышенно',
        ];
    }
}
