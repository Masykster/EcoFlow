<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Transaction;

class CarbonCalculatorService
{
    // Transport emission factor: kg CO2e per km (avg motorbike/car mix Indonesia)
    private const TRANSPORT_FACTOR = 0.21; // kg CO2e/km

    /**
     * Keyword → category slug mapping for NLP-lite merchant categorization.
     */
    private array $merchantMap = [
        'food'        => ['warung', 'resto', 'kfc', 'mcd', 'gofood', 'grabfood', 'shopeefood', 'makan'],
        'fashion'     => ['uniqlo', 'zara', 'h&m', 'shopee', 'lazada', 'tokopedia', 'pakaian', 'baju'],
        'transport'   => ['grabcar', 'gojek', 'gocar', 'maxim', 'indriver', 'ojek'],
        'electricity' => ['pln', 'listrik', 'electric'],
        'fuel'        => ['pertamina', 'spbu', 'shell', 'vivo', 'bensin', 'solar'],
        'flight'      => ['garuda', 'lion air', 'citilink', 'airasia', 'batik air'],
    ];

    /**
     * Regex-based merchant categorization → returns category slug or null.
     */
    public function categorizeByMerchant(string $merchantName): ?Category
    {
        $lower = strtolower($merchantName);

        foreach ($this->merchantMap as $slug => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($lower, $keyword)) {
                    return Category::where('slug', $slug)->first();
                }
            }
        }

        return null;
    }

    /**
     * Spend-based emission: amount (IDR) × category emission_factor.
     * emission_factor unit = kg CO2e per 1000 IDR
     */
    public function calculateFromSpend(Transaction $transaction): float
    {
        if (! $transaction->category) {
            return 0.0;
        }

        return round($transaction->amount * $transaction->category->emission_factor, 4);
    }

    /**
     * Activity-based emission: distance_km × transport factor.
     */
    public function calculateFromDistance(Transaction $transaction): float
    {
        if (! $transaction->distance_km) {
            return 0.0;
        }

        return round($transaction->distance_km * self::TRANSPORT_FACTOR, 4);
    }

    /**
     * Calculate + persist co2e on a transaction.
     */
    public function calculate(Transaction $transaction): float
    {
        $co2e = $transaction->type === 'transport'
            ? $this->calculateFromDistance($transaction)
            : $this->calculateFromSpend($transaction);

        $transaction->update(['co2e' => $co2e]);

        return $co2e;
    }
}
