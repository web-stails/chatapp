<?php

namespace App\Rules\Auth;

use App\Services\Auth\AuthService;
use Illuminate\Contracts\Validation\Rule;

class IsEmailRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return AuthService::isEmail($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        // Пользователя с таким Email не существует.
        return trans('errors.not_user_email');
    }
}
