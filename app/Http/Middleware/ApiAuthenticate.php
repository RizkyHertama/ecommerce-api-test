<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ApiAuthenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah request memiliki Bearer Token
        if (!$request->bearerToken()) {
            return response()->json([
                'success' => false,
                'message' => 'Missing Bearer token. Please provide a valid token.'
            ], 401);
        }

        // Cek apakah token yang diberikan valid
        if (!Auth::guard('sanctum')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired token. Please login again.'
            ], 401);
        }

        return $next($request);
    }
}
