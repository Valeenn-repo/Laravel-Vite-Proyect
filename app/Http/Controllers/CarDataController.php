<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Make;
use App\Models\CarModel;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="Car Data",
 *     description="Operaciones relacionadas con los datos de marcas y modelos de vehículos"
 * )
 */
class CarDataController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/car-data/grouped",
     *     summary="Obtiene marcas y modelos agrupados",
     *     tags={"Car Data"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de marcas con sus modelos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Toyota"),
     *                 @OA\Property(property="models", type="array", 
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="make_id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Corolla")
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function grouped()
    {
        $makes = DB::select("SELECT * FROM makes");
        $models = DB::select("SELECT * FROM models");

        $grouped = [];

        foreach ($makes as $make) {
            $grouped[] = [
                'id' => $make->id,
                'name' => $make->name,
                'models' => array_values(array_filter($models, function ($model) use ($make) {
                    return $model->make_id === $make->id;
                }))
            ];
        }

        return response()->json($grouped);
    }
}