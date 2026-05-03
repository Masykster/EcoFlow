<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CarbonCalculatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CalculatorController extends Controller
{
    public function __construct(private CarbonCalculatorService $calculator) {}

    /**
     * POST /api/v1/calculate/preview
     * Stateless — returns estimated CO2e WITHOUT saving to DB.
     * Used by frontend for real-time calculator feedback.
     */
    public function preview(Request $request): JsonResponse
    {
        $v = Validator::make($request->all(), [
            'category_slug' => 'required|string',
            'type'          => 'required|in:spending,transport',
            'amount'        => 'nullable|numeric|min:0',
            'distance_km'   => 'nullable|numeric|min:0',
        ], [
            'amount.min'       => 'Input harus berupa angka positif',
            'distance_km.min'  => 'Input harus berupa angka positif',
        ]);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        $category = \App\Models\Category::where('slug', $request->category_slug)->first();

        if (! $category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        if ($request->type === 'transport') {
            $co2e = round(($request->distance_km ?? 0) * 0.21, 4);
        } else {
            $co2e = round(($request->amount ?? 0) * $category->emission_factor, 4);
        }

        // Semantic label based on CO2e level
        $label = match (true) {
            $co2e <= 0.5  => ['text' => 'Rendah', 'color' => 'success'],
            $co2e <= 2.0  => ['text' => 'Sedang', 'color' => 'warning'],
            default       => ['text' => 'Tinggi', 'color' => 'danger'],
        };

        return response()->json([
            'co2e_kg'  => $co2e,
            'category' => $category->name,
            'unit'     => $category->unit,
            'label'    => $label,
        ]);
    }
}
