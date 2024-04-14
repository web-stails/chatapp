<?php

namespace App\Services\Auth;

use App\Exceptions\AuthException;
use App\Http\Requests\Auth\Restore\SetPasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthRestoreService
{
    /**
     * Генерация кода подтверждения.
     *
     * @param string $email
     * @param int    $length
     */
    public function generateCode(string $email, int $length = 4): string
    {
        $code = '';
        while (mb_strlen($code) < $length) {
            $code .= random_int(0, 9);
        }

        Cache::put(
            self::getCodeCacheKey($email),
            $code,
            now()->addHours(3)
        );

        return $code;
    }

    /**
     * Назначаем новый restore_token пользователю.
     *
     * @param string $email
     *
     * @return string
     */
    public function generateToken(string $email): string
    {
        $user                 = User::findByEmail($email);
        $user->remember_token = Str::random(40);
        $user->save();

        return $user->remember_token;
    }

    /**
     * Устанавливаем новый пароль и генерируем токен авторизации.
     *
     * @param array              $attributes
     * @param SetPasswordRequest $request
     *
     * @return string
     *@throws AuthException
     *
     */
    public function setPasswordAndGetToken(SetPasswordRequest $request): string
    {
        $user = User::where([
            'email'          => $request->email,
            'remember_token' => $request->token_re_password,
        ])
            ->firstOrFail()
        ;

        if (Hash::check($request->password, $user->password)) {
            //Пароль совпадает с предыдущим.
            throw new AuthException(trans('errors.password_matches_the_previous_one'));
        }

        $user->password       = Hash::make($request->password);
        $user->remember_token = null;
        $user->save();

        return $user->createToken('password_restored')->plainTextToken;
    }

    /**
     * Проверка кода подтверждения.
     *
     * @param string $email
     * @param string $code
     *
     * @return bool
     */
    public static function checkCode(string $email, string $code): bool
    {
        return Cache::get(self::getCodeCacheKey($email)) == $code;
    }

    /**
     * Получить ключ кеша с кодом для восстановления пароля.
     *
     * @param string $email
     *
     * @return string
     */
    public static function getCodeCacheKey(string $email): string
    {
        return 'user.' . $email . '.restore_code';
    }
}
