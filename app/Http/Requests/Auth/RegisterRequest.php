<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Rules\Auth\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'firstName' => [
                'required',
                'string',
                'max:255',
            ],
            'lastName'  => [
                'required',
                'string',
                'max:255',
            ],
            'email'     => [
                'required',
                'email:rfc',
                'unique:' . User::class,
                'max:255',
            ],
            // Во входных данных должно присутствовать => password_confirmation.
            'password'  => [
                'required',
                'confirmed',
                new PasswordRule(),
                'max:255',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'email'     => '«Почта пользователя»',
            'lastName'  => '«Имя»',
            'firstName' => '«Фамилия»',
            'password'  => '«Пароль»',
        ];
    }
}
