<?php

namespace App\Rules\Auth;

use App\Services\Auth\AuthService;
use Illuminate\Contracts\Validation\Rule;

class IsVerificationCodeRule implements Rule
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
        return AuthService::isVerificationCode($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        // Проверочный код не совпадает.
        return trans('errors.code_does_not_match');
    }
}
