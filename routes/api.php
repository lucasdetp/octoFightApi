<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\RapperController;
use App\Http\Controllers\UserController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/rappers', [RapperController::class, 'getRappers']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/rapper/{id}', [RapperController::class, 'getRapper']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'getUser']);
    Route::post('/user/change-password', [UserController::class, 'changePassword']);
    Route::post('/buy-rapper', [RapperController::class, 'buyRapper']);
    Route::get('/user/credits', [UserController::class, 'getCredits']);
});
