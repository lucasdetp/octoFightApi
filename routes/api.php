<?php

use App\Events\BattleAccepted;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\RapperController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BattleController;
use App\Models\Battle;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/rappers', [RapperController::class, 'getRappers']);
Route::get('/user/by-username/{username}', [UserController::class, 'getUserByUsername']);
Route::get('/user/{userId}/pending-invitations', [BattleController::class, 'checkPendingInvitations']);

Route::get('/test-broadcast', function () {
    $battle = Battle::find(1);
    broadcast(new BattleAccepted($battle));
    return 'Event broadcasted';
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/rapper/{id}', [RapperController::class, 'getRapper']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'getUser']);
    Route::post('/user/change-password', [UserController::class, 'changePassword']);

    Route::post('/buy-rapper', [RapperController::class, 'buyRapper']);
    Route::get('/user/credits', [UserController::class, 'getCredits']);

    Route::get('/user/deck', [UserController::class, 'getDeck']);
    Route::post('/battle/invite', [BattleController::class, 'invite']);
    Route::post('/battle/{id}/accept', [BattleController::class, 'acceptBattle']);
    Route::post('/battle/{id}/decline', [BattleController::class, 'declineBattle']);
    Route::post('/battle/{id}/resolve', [BattleController::class, 'resolveBattle']);

    // Route::get('/user/{userId}/rappers', [UserController::class, 'getRappers']);
    Route::post('/battle/{battleId}/choose-rapper', [BattleController::class, 'chooseRapper']);
    Route::get('/user/{userId}/battle', [BattleController::class, 'getActiveBattleForUser']);
    Route::get('/user/{userId}/rappers', [RapperController::class, 'getPurchasedRappers']);
    Route::get('/rapper/{rapperId}/price', [RapperController::class, 'getRapperPrice']);
});
