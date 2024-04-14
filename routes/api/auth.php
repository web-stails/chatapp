<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->withoutMiddleware('auth:sanctum')
    ->group(static function () {
        // Регистрация
        Route::post('register', [AuthController::class, 'register']);

        // Аутентификация.
        Route::post('login', [AuthController::class, 'login'])->name('login');
    });

Route::prefix('auth')->group(static function () {
    // Logout.
    Route::get('logout', [AuthController::class, 'logout']);
});