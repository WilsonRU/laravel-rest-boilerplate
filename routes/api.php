<?php

declare(strict_types=1);

use App\Http\Controllers\CoreController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('core')->group(function () {
    Route::post('login', [CoreController::class, 'login']);
    Route::post('signup', [CoreController::class, 'signup']);

    Route::post('forgot-password', [CoreController::class, 'forgotPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('reset-password', [CoreController::class, 'resetPassword']);
    });
});

// Somente Grupo ou Rotas Protegidas por Token
Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('user')->group(function () {
        Route::put('/', [UserController::class, 'updateUser']);
    });
});
