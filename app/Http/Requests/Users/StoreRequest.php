<?php

namespace App\Http\Requests\Users;

use App\Models\User;
use App\Rules\Auth\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'     => [
                'required',
                'string',
                'email',
                'unique:' . with(new User())->getTable(),
            ],
            'lastName'  => [
                'required',
                'string',
                'max:255',
            ],
            'firstName' => [
                'nullable',
                'string',
                'max:255',
            ],
            'password'  => [
                'required',
                'string',
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
