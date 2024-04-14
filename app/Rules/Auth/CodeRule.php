<?php

namespace App\Rules\Auth;

use App\Services\Auth\AuthRestoreService;
use Illuminate\Contracts\Validation\Rule;

/**
 * Проверка кода восстановления пароля.
 */
class CodeRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @param string $email
     *
     * @return void
     */
    public function __construct(protected string $email)
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
        return AuthRestoreService::checkCode($this->email, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return lang('errors.error_code');
    }
}
