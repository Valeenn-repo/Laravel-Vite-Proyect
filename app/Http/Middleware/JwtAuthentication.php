<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth; // Importar la fachada del paquete
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Log; // Importar la fachada Log

class JwtAuthentication // Nuevo nombre
{
    public function handle(Request $request, Closure $next)
    {
        // Primero intenta obtener el token del header Authorization
        $token = $request->bearerToken();

        // Si no hay token en el header, intenta obtenerlo desde la cookie
        if (!$token && $request->hasCookie('jwt_token')) {
            $token = $request->cookie('jwt_token');
        }

        Log::info('Token recibido en JwtAuthentication: ' . $token);

        if (!$token) {
            return response()->json(['error' => 'No se proporcionó un token'], 401);
        }

        try {
            $user = JWTAuth::setToken($token)->authenticate();

            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], 401);
            }

            auth('api')->setUser($user);
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'El token ha expirado'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'El token es inválido'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Error al procesar el token: ' . $e->getMessage()], 401);
        }

        return $next($request);
    }
}
