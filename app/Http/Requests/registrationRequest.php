<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class registrationRequest extends FormRequest
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
            'email' => 'required| email| unique:users',
            'name' => 'required',
            'surname' => 'required',
            'password' => 'required| min: 8| confirmed',
            'pfp' => 'required| mimes:jpeg,png,jpg',
            'lang_s' => 'required',
            'lang_t' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.email' => 'E-mail был введён некорректно',
            'email.unique' => 'Этот E-mail уже занят',
            'password.min' => 'Пароль должен быть не менее 8 символов',
            'pfp.mimes' => 'Загружать можно только в форматах jpeg, png, jpg',
            'password.confirmed' => 'Пароли не совпадают',
            'lang_s.required' => 'Вы должны выбрать язык',
            'lang_t.required' => 'Вы должны выбрать язык',
            '*.required' => 'Пожалуйста, заполните это поле'
        ];
    }
}
