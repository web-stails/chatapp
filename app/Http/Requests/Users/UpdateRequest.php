<?php

namespace App\Http\Requests\Users;

use App\Rules\Auth\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'email' => [
                'nullable',
                'string',
                'email',
            ],
            'lastName' => [
                'nullable',
                'string',
                'max:255',
            ],
            'firstName' => [
                'nullable',
                'string',
                'max:255',
            ],
            'password' => [
                'nullable',
                'string',
                new PasswordRule(),
                'max:255',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'email'       => '«Почта пользователя»',
            'lastName'        => '«Имя»',
            'firstName'     => '«Фамилия»',
            'password'    => '«Пароль»',
        ];
    }
}
