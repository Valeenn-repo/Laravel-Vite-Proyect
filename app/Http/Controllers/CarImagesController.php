<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Car Images",
 *     description="Operaciones relacionadas con las imágenes de los vehículos"
 * )
 */
class CarImagesController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/car-images",
     *     summary="Crea una nueva imagen para un vehículo",
     *     tags={"Car Images"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="car_id", type="integer", example=1, description="ID del vehículo"),
     *             @OA\Property(property="image_url", type="string", example="http://example.com/image.jpg", description="URL de la imagen"),
     *             @OA\Property(property="is_primary", type="boolean", example=true, description="Indica si es la imagen principal")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Imagen añadida correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Imagen añadida correctamente"),
     *             @OA\Property(property="id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Imagen actualizada correctamente (si ya existía)",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Imagen actualizada correctamente"),
     *             @OA\Property(property="id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error de validación"),
     *             @OA\Property(property="message", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al añadir la imagen"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'car_id' => 'required|exists:cars,id',
                'image_url' => 'required|url',
                'is_primary' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Error de validación',
                    'message' => $validator->errors()
                ], 422);
            }

            $existingImage = DB::table('car_images')
                ->where('car_id', $request->car_id)
                ->first();

            if ($existingImage) {
                $updated = DB::table('car_images')
                    ->where('id', $existingImage->id)
                    ->update([
                        'image_url' => $request->image_url,
                        'updated_at' => now()
                    ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Imagen actualizada correctamente',
                    'id' => $existingImage->id
                ], 200);
            }

            $imageData = [
                'car_id' => $request->car_id,
                'image_url' => $request->image_url,
                'is_primary' => $request->input('is_primary', true),
                'created_at' => now(),
                'updated_at' => now()
            ];

            $id = DB::table('car_images')->insertGetId($imageData);

            return response()->json([
                'success' => true,
                'message' => 'Imagen añadida correctamente',
                'id' => $id
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error añadiendo imagen: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'error' => 'Error al añadir la imagen',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/car-images/{id}",
     *     summary="Actualiza una imagen existente",
     *     tags={"Car Images"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la imagen a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="car_id", type="integer", example=1, description="ID del vehículo"),
     *             @OA\Property(property="image_url", type="string", example="http://example.com/image.jpg", description="URL de la imagen")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Imagen actualizada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Imagen actualizada correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="La imagen no pertenece al vehículo",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="La imagen no pertenece al vehículo especificado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Imagen no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Imagen no encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error de validación"),
     *             @OA\Property(property="message", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al actualizar la imagen"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'car_id' => 'required|exists:cars,id',
                'image_url' => 'required|url'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Error de validación',
                    'message' => $validator->errors()
                ], 422);
            }

            $image = DB::table('car_images')->where('id', $id)->first();
            if (!$image) {
                return response()->json([
                    'error' => 'Imagen no encontrada'
                ], 404);
            }

            if ($image->car_id != $request->car_id) {
                return response()->json([
                    'error' => 'La imagen no pertenece al vehículo especificado'
                ], 403);
            }

            $updated = DB::table('car_images')
                ->where('id', $id)
                ->update([
                    'image_url' => $request->image_url
                ]);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Imagen actualizada correctamente'
                ], 200);
            } else {
                return response()->json([
                    'error' => 'No se pudo actualizar la imagen'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error actualizando imagen: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'error' => 'Error al actualizar la imagen',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/car-images/{id}",
     *     summary="Elimina una imagen",
     *     tags={"Car Images"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la imagen a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Imagen eliminada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Imagen eliminada correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Imagen no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Imagen no encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al eliminar la imagen"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $image = DB::table('car_images')->where('id', $id)->first();
            if (!$image) {
                return response()->json([
                    'error' => 'Imagen no encontrada'
                ], 404);
            }

            $deleted = DB::table('car_images')->where('id', $id)->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Imagen eliminada correctamente'
                ], 200);
            } else {
                return response()->json([
                    'error' => 'No se pudo eliminar la imagen'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error eliminando imagen: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Error al eliminar la imagen',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}