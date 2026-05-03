<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SnapBiController extends Controller
{
    /**
     * GET /api/v1/snap/bank-statement
     * Simulates SNAP BI bank-statement pull response.
     * Headers required: X-PARTNER-ID, X-TIMESTAMP, X-SIGNATURE (validated by SnapBiMiddleware).
     */
    public function bankStatement(Request $request): JsonResponse
    {
        // Mocked bank statement data mimicking SNAP BI response structure
        return response()->json([
            'responseCode'    => '2001500',
            'responseMessage' => 'Successful',
            'referenceNo'     => 'ECOSTEP-' . now()->format('YmdHis'),
            'partnerReferenceNo' => $request->header('X-PARTNER-ID') . '-' . uniqid(),
            'accountNo'       => '***' . rand(1000, 9999),
            'currency'        => 'IDR',
            'transactions'    => [
                [
                    'transactionDate'   => now()->subDays(1)->toDateString(),
                    'merchantName'      => 'GOPAY*WARUNG MAKAN SEDERHANA',
                    'amount'            => '45000.00',
                    'transactionType'   => 'DEBIT',
                    'referenceNumber'   => 'TXN-' . uniqid(),
                ],
                [
                    'transactionDate'   => now()->subDays(2)->toDateString(),
                    'merchantName'      => 'GRAB*GRABCAR',
                    'amount'            => '32000.00',
                    'transactionType'   => 'DEBIT',
                    'referenceNumber'   => 'TXN-' . uniqid(),
                ],
                [
                    'transactionDate'   => now()->subDays(3)->toDateString(),
                    'merchantName'      => 'SHOPEE*UNIQLO',
                    'amount'            => '299000.00',
                    'transactionType'   => 'DEBIT',
                    'referenceNumber'   => 'TXN-' . uniqid(),
                ],
            ],
        ]);
    }
}
