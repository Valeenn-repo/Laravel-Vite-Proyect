<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="Dealerships",
 *     description="Operaciones relacionadas con los concesionarios"
 * )
 */
class DealershipsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/dealerships",
     *     summary="Obtiene todos los concesionarios",
     *     tags={"Dealerships"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de concesionarios",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Concesionario Central")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al obtener concesionarios"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $dealerships = DB::table('dealerships')
                ->select('id', 'name')
                ->get();

            return response()->json($dealerships);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener concesionarios',
                'message' => $e->getMessage(),
                'trace' => env('APP_DEBUG', false) ? $e->getTraceAsString() : null
            ], 500);
        }
    }
}