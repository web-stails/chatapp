<?php

use App\Http\Controllers\Chats\ChatController;
use Illuminate\Support\Facades\Route;

Route::apiResource('chats', ChatController::class)->only(['index', 'show', 'destroy']);

Route::post('chats/{user}', [ChatController::class, 'store']);
