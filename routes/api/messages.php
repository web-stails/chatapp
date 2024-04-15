<?php

use App\Http\Controllers\Messages\MessageController;
use Illuminate\Support\Facades\Route;

// TODO в текущих роутах предпочел бы использовать ->shallow();
Route::apiResource('chats/{chat}/messages', MessageController::class)->except('show');
