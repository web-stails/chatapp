<?php

use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    foreach (glob(base_path('routes/api/*.php')) as $file) {
        Route::middleware(['api', 'auth:sanctum'])->group($file);
    }
});
