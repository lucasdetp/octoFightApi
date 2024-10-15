<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\RapperController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/rappers', [RapperController::class, 'getRappers']);
Route::middleware('auth:sanctum')->post('/buy-rapper', [RapperController::class, 'buyRapper']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/rapper/{id}', [RapperController::class, 'getRapper']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});
