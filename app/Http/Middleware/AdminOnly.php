<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user || $user->role !== 'admin') {
                return response()->json(['error' => 'Acceso solo para administradores'], 403);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'No autenticado'], 401);
        }
        return $next($request);
    }
}