<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = null;
        try {
            // Intentar obtener el usuario autenticado con JWT
            if ($token = $request->bearerToken()) {
                $user = JWTAuth::setToken($token)->authenticate();
            }
        } catch (\Exception $e) {
            // Si falla (token inválido, expirado, etc.), user será null
            Log::info('Error al obtener usuario JWT: ' . $e->getMessage());
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user, // Compartir el usuario autenticado con JWT
            ],
        ];
    }
}