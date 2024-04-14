<?php

namespace App\Services\Auth;

use App\Exceptions\AuthException;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    /**
     * Регистрация пользователя.
     *
     * @param array $request
     * @param array $attributes
     *
     * @return User
     */
    public function register(array $attributes): void
    {
        DB::transaction(static function () use ($attributes) {
            $attributes['password']       = Hash::make($attributes['password']);
            $remember_token               = Str::random(40);
            $attributes['remember_token'] = $remember_token;

            User::create($attributes);
        });
    }

    /**
     * Авторизация пользователя.
     *
     * @param array $attributes
     *
     * @throws \Exception
     *
     * @return array
     */
    public function login(array $attributes): array
    {
        $user = User::where('email', $attributes['email'])->firstOrFail();

        if (is_null($user) || !Hash::check($attributes['password'], $user->password)) {
            //Пользователя с такими учетными данными не существует.
            throw new AuthException(trans('errors.not_user'));
        }

        $token = $user->createToken('login', empty($attributes['remember']) ? ['*'] : ['remember']);

        return ['token' => $token->plainTextToken, 'user' => $user];
    }

    /**
     * Выход пользователя.
     *
     * @return void
     */
    public function logout(): void
    {
        request()->user()->currentAccessToken()->delete();
    }

    /**
     * Есть ли пользователь с таким Email.
     *
     * @param string $email
     *
     * @return bool
     */
    public static function isEmail(string $email): bool
    {
        return User::where('email', $email)->exists();
    }
}
