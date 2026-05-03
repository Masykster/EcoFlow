<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BasicAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = config('services.basic_auth.user', 'admin');
        $pass = config('services.basic_auth.pass', 'secret');

        if ($request->getUser() !== $user || $request->getPassword() !== $pass) {
            return response()->json(['message' => 'Unauthorized'], 401)
                ->header('WWW-Authenticate', 'Basic realm="Admin"');
        }

        return $next($request);
    }
}
