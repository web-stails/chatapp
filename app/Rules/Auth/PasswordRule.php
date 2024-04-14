<?php

namespace App\Rules\Auth;

use Illuminate\Container\Container;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\UncompromisedVerifier;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class PasswordRule extends Password implements Rule, DataAwareRule, ValidatorAwareRule
{
    protected const PASSWORD_MIN_LENGTH = 6;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(self::PASSWORD_MIN_LENGTH);
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
        $this->messages = [];

        $validator = Validator::make(
            $this->data,
            [$attribute => $this->customRules],
            $this->validator->customMessages,
            $this->validator->customAttributes
        )->after(function ($validator) use ($attribute, $value) {
            if (!is_string($value)) {
                return;
            }
            $value = (string) $value;

            // Поле «пароль» должен содержать не менее :min символов
            if (\strlen($value) < self::PASSWORD_MIN_LENGTH) {
                $validator->errors()->add($attribute, trans('errors.password:0', ['min' => self::PASSWORD_MIN_LENGTH]));
            }

            // Поле «пароль» должен содержать по крайней мере одну прописную и одну строчную букву
            if (!preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/u', $value)) {
                $validator->errors()->add($attribute, trans('errors.password:1'));
            }

            // Поле «пароль» должно содержать хотя бы одну букву
            if (!preg_match('/\pL/u', $value)) {
                $validator->errors()->add($attribute, trans('errors.password:2'));
            }

            // Поле «пароль» должно содержать хотя бы одно число.
            if (!preg_match('/\pN/u', $value)) {
                $validator->errors()->add($attribute, trans('errors.password:3'));
            }
        });

        if ($validator->fails()) {
            return $this->fail($validator->messages()->all());
        }

        if ($this->uncompromised && !Container::getInstance()->make(UncompromisedVerifier::class)->verify([
            'value'     => $value,
            'threshold' => $this->compromisedThreshold,
        ])) {
            return $this->fail(
                'The given :attribute has appeared in a data leak. Please choose a different :attribute.'
            );
        }

        return true;
    }
}
