<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Services\Auth\AuthService;


class AuthController extends Controller
{
    public function __construct(protected AuthService $authServices)
    {
    }

    /**
     * Регистрация.
     *
     * @param RegisterRequest $request
     *
     * @return mixed
     */
    public function register(RegisterRequest $request): void
    {
        $this->authServices->register($request->validated());
    }

    /**
     * Авторизация.
     *
     * @param LoginRequest $request
     *
     * @return LoginResource
     */
    public function login(LoginRequest $request): LoginResource
    {
        ['token' => $token, 'user' => $user] = $this->authServices->login($request->validated());

        $user->token = $token;

        return LoginResource::make($user);
    }

    /**
     * Logout.
     *
     * @return void
     */
    public function logout(): void
    {
        $this->authServices->logout();
    }
}
