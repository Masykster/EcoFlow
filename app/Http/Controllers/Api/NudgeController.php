<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NudgeService;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class NudgeController extends Controller
{
    public function __construct(private NudgeService $nudge) {}

    public function greenNudges(): JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();

        return response()->json([
            'nudges' => $this->nudge->getGreenNudges($user),
        ]);
    }
}
