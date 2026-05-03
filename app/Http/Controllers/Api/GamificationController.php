<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GamificationService;
use Illuminate\Http\JsonResponse;

class GamificationController extends Controller
{
    public function __construct(private GamificationService $gamification) {}

    public function leaderboard(): JsonResponse
    {
        return response()->json([
            'leaderboard' => $this->gamification->getLeaderboard(),
        ]);
    }
}
