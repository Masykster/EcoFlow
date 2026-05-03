<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CarbonCalculatorService;
use App\Services\GamificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class TransactionController extends Controller
{
    public function __construct(
        private CarbonCalculatorService $calculator,
        private GamificationService $gamification,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();

        $query = $user->transactions()->with('category:id,name,slug')->latest('transacted_at');

        // Filters: ?category_slug=food&type=spending&from=2026-04-01&to=2026-04-30
        if ($request->filled('category_slug')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category_slug));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('from')) {
            $query->where('transacted_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('transacted_at', '<=', $request->to . ' 23:59:59');
        }

        return response()->json($query->paginate(15));
    }

    public function store(Request $request): JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();

        $v = Validator::make($request->all(), [
            'merchant_name' => 'required|string|max:255',
            'amount'        => 'required|numeric|min:0',
            'type'          => 'required|in:spending,transport',
            'distance_km'   => 'nullable|numeric|min:0',
            'transacted_at' => 'nullable|date',
        ], [
            'amount.min'      => 'Input harus berupa angka positif',
            'distance_km.min' => 'Input harus berupa angka positif',
            'amount.numeric'  => 'Nominal harus berupa angka',
            'type.in'         => 'Tipe transaksi tidak valid',
        ]);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        $category = $this->calculator->categorizeByMerchant($request->merchant_name);

        $transaction = $user->transactions()->create([
            'merchant_name' => $request->merchant_name,
            'amount'        => $request->amount,
            'category_id'   => $category?->id,
            'type'          => $request->type,
            'distance_km'   => $request->distance_km,
            'transacted_at' => $request->transacted_at ?? now(),
        ]);

        $co2e = $this->calculator->calculate($transaction->load('category'));
        $this->gamification->awardPoints($user, $transaction);

        return response()->json([
            'transaction' => $transaction->fresh(['category']),
            'co2e_kg'     => $co2e,
        ], 201);
    }

    public function destroy(int $id): JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();

        $transaction = $user->transactions()->find($id);

        if (! $transaction) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        $transaction->delete();

        return response()->json(['message' => 'Transaksi berhasil dihapus']);
    }
}

