<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CarbonCalculatorService;
use App\Services\GamificationService;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class CarbonController extends Controller
{
    public function __construct(
        private CarbonCalculatorService $calculator,
        private GamificationService $gamification,
    ) {}

    /**
     * POST /api/v1/analyze/carbon
     * Re-calculate CO2e for all user transactions and award points.
     */
    public function analyze(): JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();

        $transactions = $user->transactions()->with('category')->get();
        $totalCo2e    = 0.0;
        $processed    = 0;

        foreach ($transactions as $trx) {
            $co2e = $this->calculator->calculate($trx);
            $totalCo2e += $co2e;
            $this->gamification->awardPoints($user, $trx);
            $processed++;
        }

        $this->gamification->checkBadges($user);

        // Social norm comparison: user vs average of all users
        $avgCo2e = \App\Models\Transaction::whereNotNull('co2e')
            ->avg('co2e') ?? 0;

        return response()->json([
            'transactions_processed' => $processed,
            'total_co2e_kg'          => round($totalCo2e, 4),
            'avg_user_co2e_kg'       => round($avgCo2e, 4),
            'vs_average'             => $avgCo2e > 0
                ? round((($totalCo2e / max($processed, 1)) - $avgCo2e) / $avgCo2e * 100, 1) . '%'
                : 'N/A',
        ]);
    }
}
