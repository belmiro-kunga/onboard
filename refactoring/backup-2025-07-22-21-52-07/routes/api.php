<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotas autenticadas da API
Route::middleware(['auth:sanctum'])->group(function () {
    // Quiz API
    Route::get('/quiz/{quiz}', [App\Http\Controllers\Api\QuizController::class, 'show']);
    
    // Gamification API
    Route::get('/gamification/stats', [App\Http\Controllers\GamificationController::class, 'getStats']);
    Route::get('/gamification/ranking', [App\Http\Controllers\GamificationController::class, 'getRanking']);
    Route::post('/gamification/streak', [App\Http\Controllers\GamificationController::class, 'updateStreak']);
    Route::get('/gamification/achievements/check', [App\Http\Controllers\GamificationController::class, 'checkAchievements']);
    
    // Test route (apenas em desenvolvimento)
    Route::post('/gamification/add-points', [App\Http\Controllers\GamificationController::class, 'addPoints']);
});
