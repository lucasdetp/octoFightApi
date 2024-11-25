<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\RapperController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BattleController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/rappers', [RapperController::class, 'getRappers']);
Route::get('/user/by-username/{username}', [UserController::class, 'getUserByUsername']);
Route::get('/user/{userId}/pending-invitations', [BattleController::class, 'checkPendingInvitations']);

Route::get('/test-broadcast', function () {
    $battle = Battle::find(1); // Assurez-vous qu'un battle avec l'ID 1 existe
    broadcast(new BattleAccepted($battle));
    return 'Event broadcasted';
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/rapper/{id}', [RapperController::class, 'getRapper']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'getUser']);
    Route::post('/user/change-password', [UserController::class, 'changePassword']);
    Route::post('/buy-rapper', [RapperController::class, 'buyRapper']);
    Route::post('/battle/invite', [BattleController::class, 'invite']);
    Route::post('/battle/{id}/accept', [BattleController::class, 'acceptBattle']);
    Route::post('/battle/{id}/decline', [BattleController::class, 'declineBattle']);
    Route::post('/battle/{id}/resolve', [BattleController::class, 'resolveBattle']);
    Route::get('/user/{userId}/rappers', [UserController::class, 'getRappers']);
    Route::post('/battle/{battleId}/choose-rapper', [BattleController::class, 'chooseRapper']);
});
