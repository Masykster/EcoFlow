<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class DashboardController extends Controller
{
    public function weekly(): JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();

        $data = $this->aggregate($user->id, now()->subWeek(), now());

        return response()->json($data);
    }

    public function monthly(): JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();

        $data = $this->aggregate($user->id, now()->subMonth(), now());

        return response()->json($data);
    }

    public function daily(): JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();

        $data = $this->aggregate($user->id, now()->startOfDay(), now());

        return response()->json($data);
    }

    private function aggregate(int $userId, $from, $to): array
    {
        $rows = Transaction::query()
            ->where('user_id', $userId)
            ->whereBetween('transacted_at', [$from, $to])
            ->whereNotNull('co2e')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select(
                'categories.name as category',
                DB::raw('SUM(transactions.co2e) as total_co2e'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('categories.name')
            ->orderByDesc('total_co2e')
            ->get();

        $total = Transaction::where('user_id', $userId)
            ->whereBetween('transacted_at', [$from, $to])
            ->whereNotNull('co2e')
            ->sum('co2e');

        return [
            'period'       => ['from' => $from->toDateString(), 'to' => $to->toDateString()],
            'total_co2e_kg' => round($total, 4),
            'by_category'  => $rows,
        ];
    }
}
