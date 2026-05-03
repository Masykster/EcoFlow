<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialiteController extends Controller
{
    public function redirectToGoogle(): \Symfony\Component\HttpFoundation\RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback(): JsonResponse
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Google OAuth failed: ' . $e->getMessage()], 401);
        }

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name'              => $googleUser->getName(),
                'password'          => bcrypt(Str::random(24)),
                'api_key'           => Str::random(64),
                'email_verified_at' => now(),
            ]
        );

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user'       => $user->only('id', 'name', 'email'),
            'token'      => $token,
            'type'       => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ]);
    }
}
