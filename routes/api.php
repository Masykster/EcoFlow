<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CalculatorController;
use App\Http\Controllers\Api\CarbonController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\GamificationController;
use App\Http\Controllers\Api\NudgeController;
use App\Http\Controllers\Api\SnapBiController;
use App\Http\Controllers\Api\SocialiteController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // ── Auth (Public) ────────────────────────────────────────────────────────
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login',    [AuthController::class, 'login']);
        Route::post('logout',   [AuthController::class, 'logout'])->middleware('jwt');
        Route::get('me',        [AuthController::class, 'me'])->middleware('jwt');

        // Google OAuth
        Route::get('google/redirect', [SocialiteController::class, 'redirectToGoogle']);
        Route::get('google/callback', [SocialiteController::class, 'handleGoogleCallback']);
    });

    // ── Public calculator preview (no auth needed for real-time UX) ──────────
    Route::post('calculate/preview', [CalculatorController::class, 'preview']);

    // ── JWT-protected endpoints ───────────────────────────────────────────────
    Route::middleware('jwt')->group(function () {
        Route::get('transactions',        [TransactionController::class, 'index']);
        Route::post('transactions',       [TransactionController::class, 'store']);
        Route::delete('transactions/{id}',[TransactionController::class, 'destroy']);

        Route::post('analyze/carbon', [CarbonController::class, 'analyze']);

        Route::get('dashboard/daily',   [DashboardController::class, 'daily']);
        Route::get('dashboard/weekly',  [DashboardController::class, 'weekly']);
        Route::get('dashboard/monthly', [DashboardController::class, 'monthly']);

        Route::get('gamification/leaderboard', [GamificationController::class, 'leaderboard']);

        Route::get('tips/green-nudges', [NudgeController::class, 'greenNudges']);
    });

    // ── SNAP BI Simulation (API Key + SNAP headers) ───────────────────────────
    Route::middleware(['api.key', 'snap.bi'])->group(function () {
        Route::get('snap/bank-statement', [SnapBiController::class, 'bankStatement']);
    });

    // ── Admin (Basic Auth) ────────────────────────────────────────────────────
    // Placeholder: add admin controllers here with middleware('basic.auth')
});

