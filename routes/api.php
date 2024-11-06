<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\RapperController;
use App\Http\Controllers\UserController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/rappers', [RapperController::class, 'getRappers']);
Route::get('/rapper/{id}', [RapperController::class, 'getRapper']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'getUser']);
    Route::middleware('auth:sanctum')->post('/user/change-password', [UserController::class, 'changePassword']);
});
