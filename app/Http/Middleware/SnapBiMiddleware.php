<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SnapBiMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $partnerId = $request->header('X-PARTNER-ID');
        $timestamp = $request->header('X-TIMESTAMP');
        $signature = $request->header('X-SIGNATURE');

        if (! $partnerId || ! $timestamp || ! $signature) {
            return response()->json([
                'message' => 'SNAP BI headers required: X-PARTNER-ID, X-TIMESTAMP, X-SIGNATURE',
            ], 400);
        }

        if ($partnerId !== config('app.snap_partner_id', env('SNAP_PARTNER_ID', 'ECOSTEP-001'))) {
            return response()->json(['message' => 'Invalid SNAP Partner ID'], 401);
        }

        // Validate timestamp within ±5 min to prevent replay attacks
        $requestTime = strtotime($timestamp);
        if (! $requestTime || abs(time() - $requestTime) > 300) {
            return response()->json(['message' => 'SNAP timestamp expired or invalid'], 401);
        }

        // Simulate signature check: HMAC-SHA256(partnerId + ":" + timestamp, APP_KEY)
        $expected = hash_hmac('sha256', $partnerId . ':' . $timestamp, config('app.key'));
        if (! hash_equals($expected, $signature)) {
            return response()->json(['message' => 'SNAP signature mismatch'], 401);
        }

        return $next($request);
    }
}
