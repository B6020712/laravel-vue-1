<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'authenticate']);
Route::post('logout', [AuthController::class, 'sign_out']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'get_user']);
});

