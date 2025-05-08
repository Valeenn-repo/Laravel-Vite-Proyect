<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Cars",
 *     description="Operaciones relacionadas con los vehículos"
 * )
 */
class CarController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/cars",
     *     summary="Obtiene todos los vehículos",
     *     tags={"Cars"},
     *     @OA\Parameter(
     *         name="make_id",
     *         in="query",
     *         description="Filtrar por ID de marca",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="model_id",
     *         in="query",
     *         description="Filtrar por ID de modelo",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="body_type",
     *         in="query",
     *         description="Filtrar por tipo de carrocería",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="fuel_type",
     *         in="query",
     *         description="Filtrar por tipo de combustible",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="transmission_type",
     *         in="query",
     *         description="Filtrar por tipo de transmisión",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="max_price",
     *         in="query",
     *         description="Filtrar por precio máximo",
     *         required=false,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="max_mileage",
     *         in="query",
     *         description="Filtrar por kilometraje máximo",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de vehículos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="make_name", type="string", example="Toyota"),
     *                 @OA\Property(property="model_name", type="string", example="Corolla"),
     *                 @OA\Property(property="year", type="integer", example=2023),
     *                 @OA\Property(property="image_url", type="string", example="http://example.com/image.jpg", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al obtener vehículos"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $carsTable = DB::getSchemaBuilder()->getColumnListing('cars');
            $hasModelId = in_array('model_id', $carsTable);
            $hasCarImagesTable = Schema::hasTable('car_images');
            $carImagesColumns = $hasCarImagesTable ? DB::getSchemaBuilder()->getColumnListing('car_images') : [];
            $hasIsPrimaryColumn = in_array('is_primary', $carImagesColumns);

            $query = DB::table('cars')
                ->when($hasModelId, function ($q) {
                    $q->leftJoin('models', 'cars.model_id', '=', 'models.id')
                        ->leftJoin('makes', 'models.make_id', '=', 'makes.id')
                        ->addSelect(DB::raw("COALESCE(makes.name, 'Sin marca') as make_name"))
                        ->addSelect(DB::raw("COALESCE(models.name, 'Sin modelo') as model_name"));
                });

            if ($hasCarImagesTable) {
                if ($hasIsPrimaryColumn) {
                    $query->leftJoin('car_images', function ($join) {
                        $join->on('cars.id', '=', 'car_images.car_id')
                            ->where('car_images.is_primary', true);
                    });
                } else {
                    $query->leftJoin('car_images', function ($join) {
                        $join->on('cars.id', '=', 'car_images.car_id');
                    });
                }

                if (in_array('image_url', $carImagesColumns)) {
                    $query->addSelect('cars.*', 'car_images.image_url');
                } else {
                    $query->addSelect('cars.*');
                }
            } else {
                $query->addSelect('cars.*');
            }

            if ($request->has('make_id') && $hasModelId) {
                $query->where('models.make_id', $request->make_id);
            }
            if ($request->has('model_id') && $hasModelId) {
                $query->where('cars.model_id', $request->model_id);
            }
            if ($request->has('body_type') && in_array('body_type', $carsTable)) {
                $query->where('cars.body_type', $request->body_type);
            }
            if ($request->has('fuel_type') && in_array('fuel_type', $carsTable)) {
                $query->where('cars.fuel_type', $request->fuel_type);
            }
            if ($request->has('transmission_type') && in_array('transmission_type', $carsTable)) {
                $query->where('cars.transmission_type', $request->transmission_type);
            }
            if ($request->has('max_price') && in_array('price', $carsTable)) {
                $query->where('cars.price', '<=', $request->max_price);
            }
            if ($request->has('max_mileage') && in_array('mileage', $carsTable)) {
                $query->where('cars.mileage', '<=', $request->max_mileage);
            }

            $cars = $query->get();

            return response()->json($cars);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Error al obtener vehículos',
                'message' => $e->getMessage(),
                'trace'   => env('APP_DEBUG', false) ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/cars/{id}",
     *     summary="Obtiene un vehículo específico",
     *     tags={"Cars"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del vehículo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del vehículo",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="make_name", type="string", example="Toyota"),
     *             @OA\Property(property="model_name", type="string", example="Corolla"),
     *             @OA\Property(property="year", type="integer", example=2023),
     *             @OA\Property(property="photos", type="array", 
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="url", type="string", example="http://example.com/image.jpg"),
     *                     @OA\Property(property="is_primary", type="boolean", example=true, nullable=true)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vehículo no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Vehículo no encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al obtener el vehículo"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $carsTable = DB::getSchemaBuilder()->getColumnListing('cars');
            $hasModelId = in_array('model_id', $carsTable);
            $hasCarImagesTable = Schema::hasTable('car_images');

            $query = DB::table('cars')
                ->where('cars.id', $id)
                ->when($hasModelId, function ($q) {
                    $q->leftJoin('models', 'cars.model_id', '=', 'models.id')
                        ->leftJoin('makes', 'models.make_id', '=', 'makes.id')
                        ->addSelect(DB::raw("COALESCE(makes.name, 'Sin marca') as make_name"))
                        ->addSelect(DB::raw("COALESCE(models.name, 'Sin modelo') as model_name"));
                })
                ->addSelect('cars.*');

            $car = $query->first();
            if (!$car) {
                return response()->json(['error' => 'Vehículo no encontrado'], 404);
            }

            $carDetails = (array) $car;

            if ($hasCarImagesTable) {
                $carImagesColumns = DB::getSchemaBuilder()->getColumnListing('car_images');

                if (in_array('car_id', $carImagesColumns) && in_array('image_url', $carImagesColumns)) {
                    $photoQuery = DB::table('car_images')->where('car_id', $id);

                    if (in_array('is_primary', $carImagesColumns)) {
                        $photoQuery->select('id', 'image_url as url', 'is_primary');
                    } else {
                        $photoQuery->select('id', 'image_url as url');
                    }

                    $photos = $photoQuery->get();
                } else {
                    $photos = collect([]);
                }

                $carDetails['photos'] = $photos;
            } else {
                $carDetails['photos'] = [];
            }

            return response()->json($carDetails);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Error al obtener el vehículo',
                'message' => $e->getMessage(),
                'trace'   => env('APP_DEBUG', false) ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/cars",
     *     summary="Crea un nuevo vehículo",
     *     tags={"Cars"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="make_id", type="integer", example=1, description="ID de la marca"),
     *             @OA\Property(property="modelo", type="string", example="Corolla", description="Nombre del modelo"),
     *             @OA\Property(property="dealership_id", type="integer", example=1, description="ID del concesionario"),
     *             @OA\Property(property="vin", type="string", example="1HGCM82633A004352", description="Número de identificación del vehículo"),
     *             @OA\Property(property="year", type="integer", example=2023, description="Año del vehículo"),
     *             @OA\Property(property="price", type="number", example=25000.00, description="Precio del vehículo"),
     *             @OA\Property(property="mileage", type="integer", example=10000, description="Kilometraje", nullable=true),
     *             @OA\Property(property="color", type="string", example="Red", description="Color", nullable=true),
     *             @OA\Property(property="transmission_type", type="string", example="Automatic", description="Tipo de transmisión", nullable=true),
     *             @OA\Property(property="fuel_type", type="string", example="Gasoline", description="Tipo de combustible", nullable=true),
     *             @OA\Property(property="status", type="string", example="available", description="Estado del vehículo", nullable=true),
     *             @OA\Property(property="imagen", type="string", example="http://example.com/image.jpg", description="URL de la imagen", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Vehículo añadido correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Vehículo añadido correctamente"),
     *             @OA\Property(property="car_id", type="integer", example=1)
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
     *             @OA\Property(property="error", type="string", example="Error al añadir el vehículo"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            Log::info('Datos recibidos en store:', $request->all());

            $validator = Validator::make($request->all(), [
                'make_id' => 'required|numeric',
                'modelo' => 'required|string|max:100',
                'dealership_id' => 'required|numeric',
                'vin' => 'required|string|size:17',
                'year' => 'required|numeric|min:1900|max:2100',
                'price' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Error de validación',
                    'message' => $validator->errors()
                ], 422);
            }

            if (!Schema::hasTable('makes')) {
                throw new \Exception("La tabla 'makes' no existe en la base de datos");
            }

            if (!Schema::hasTable('models')) {
                throw new \Exception("La tabla 'models' no existe en la base de datos");
            }

            if (!Schema::hasTable('dealerships')) {
                throw new \Exception("La tabla 'dealerships' no existe en la base de datos");
            }

            $make = DB::table('makes')->where('id', $request->make_id)->first();
            if (!$make) {
                return response()->json([
                    'error' => 'La marca seleccionada no existe',
                    'make_id' => $request->make_id
                ], 422);
            }

            $dealership = DB::table('dealerships')->where('id', $request->dealership_id)->first();
            if (!$dealership) {
                return response()->json([
                    'error' => 'El concesionario seleccionado no existe',
                    'dealership_id' => $request->dealership_id
                ], 422);
            }

            $existingCar = DB::table('cars')->where('vin', $request->vin)->first();
            if ($existingCar) {
                return response()->json([
                    'error' => 'Ya existe un vehículo con este VIN',
                    'vin' => $request->vin
                ], 422);
            }

            $existingModel = DB::table('models')
                ->where('name', $request->modelo)
                ->where('make_id', $request->make_id)
                ->first();

            if ($existingModel) {
                $modelId = $existingModel->id;
            } else {
                $modelColumns = DB::getSchemaBuilder()->getColumnListing('models');
                $modelData = [
                    'name' => $request->modelo,
                    'make_id' => $request->make_id
                ];

                if (in_array('created_at', $modelColumns)) {
                    $modelData['created_at'] = now();
                }
                if (in_array('updated_at', $modelColumns)) {
                    $modelData['updated_at'] = now();
                }

                $modelId = DB::table('models')->insertGetId($modelData);
            }

            $carColumns = DB::getSchemaBuilder()->getColumnListing('cars');

            $carData = [
                'model_id' => $modelId,
                'dealership_id' => $request->dealership_id,
                'vin' => $request->vin,
                'year' => $request->year,
                'price' => $request->price
            ];

            if (in_array('created_at', $carColumns)) {
                $carData['created_at'] = now();
            }
            if (in_array('updated_at', $carColumns)) {
                $carData['updated_at'] = now();
            }

            if ($request->has('mileage') && $request->mileage !== null && $request->mileage !== '' && in_array('mileage', $carColumns)) {
                $carData['mileage'] = $request->mileage;
            }

            if ($request->has('color') && $request->color !== null && $request->color !== '' && in_array('color', $carColumns)) {
                $carData['color'] = $request->color;
            }

            if ($request->has('transmission_type') && $request->transmission_type !== null && $request->transmission_type !== '' && in_array('transmission_type', $carColumns)) {
                $carData['transmission_type'] = $request->transmission_type;
            }

            if ($request->has('fuel_type') && $request->fuel_type !== null && $request->fuel_type !== '' && in_array('fuel_type', $carColumns)) {
                $carData['fuel_type'] = $request->fuel_type;
            }

            if ($request->has('status') && $request->status !== null && $request->status !== '' && in_array('status', $carColumns)) {
                $carData['status'] = $request->status;
            } elseif (in_array('status', $carColumns)) {
                $carData['status'] = 'available';
            }

            $carId = DB::table('cars')->insertGetId($carData);
            Log::info("Coche insertado con ID: " . $carId);

            if ($request->has('imagen') && $request->imagen !== null && $request->imagen !== '') {
                if (Schema::hasTable('car_images')) {
                    $carImagesColumns = DB::getSchemaBuilder()->getColumnListing('car_images');

                    if (in_array('car_id', $carImagesColumns) && in_array('image_url', $carImagesColumns)) {
                        $imageData = [
                            'car_id' => $carId,
                            'image_url' => $request->imagen
                        ];

                        if (in_array('created_at', $carImagesColumns)) {
                            $imageData['created_at'] = now();
                        }
                        if (in_array('uploaded_at', $carImagesColumns)) {
                            $imageData['uploaded_at'] = now();
                        }

                        if (in_array('is_primary', $carImagesColumns)) {
                            $imageData['is_primary'] = true;
                        }

                        DB::table('car_images')->insert($imageData);
                        Log::info("Imagen insertada para el coche ID: " . $carId);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vehículo añadido correctamente',
                'car_id' => $carId
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al añadir vehículo: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'error' => 'Error al añadir el vehículo',
                'message' => $e->getMessage(),
                'trace' => env('APP_DEBUG', false) ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/cars/{id}",
     *     summary="Actualiza un vehículo existente",
     *     tags={"Cars"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del vehículo a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="make_id", type="integer", example=1, description="ID de la marca"),
     *             @OA\Property(property="modelo", type="string", example="Corolla", description="Nombre del modelo"),
     *             @OA\Property(property="dealership_id", type="integer", example=1, description="ID del concesionario"),
     *             @OA\Property(property="vin", type="string", example="1HGCM82633A004352", description="Número de identificación del vehículo"),
     *             @OA\Property(property="year", type="integer", example=2023, description="Año del vehículo"),
     *             @OA\Property(property="price", type="number", example=25000.00, description="Precio del vehículo"),
     *             @OA\Property(property="mileage", type="integer", example=10000, description="Kilometraje", nullable=true),
     *             @OA\Property(property="color", type="string", example="Red", description="Color", nullable=true),
     *             @OA\Property(property="transmission_type", type="string", example="Automatic", description="Tipo de transmisión", nullable=true),
     *             @OA\Property(property="fuel_type", type="string", example="Gasoline", description="Tipo de combustible", nullable=true),
     *             @OA\Property(property="status", type="string", example="available", description="Estado del vehículo", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vehículo actualizado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Vehículo actualizado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vehículo no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Vehículo no encontrado")
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
     *             @OA\Property(property="error", type="string", example="Error al actualizar el vehículo"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            Log::info('Datos recibidos en update:', $request->all());

            $car = DB::table('cars')->where('id', $id)->first();
            if (!$car) {
                return response()->json([
                    'error' => 'Vehículo no encontrado'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'make_id' => 'required|numeric',
                'modelo' => 'required|string|max:100',
                'dealership_id' => 'required|numeric',
                'vin' => 'required|string|size:17',
                'year' => 'required|numeric|min:1900|max:2100',
                'price' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Error de validación',
                    'message' => $validator->errors()
                ], 422);
            }

            if (!Schema::hasTable('makes')) {
                throw new \Exception("La tabla 'makes' no existe en la base de datos");
            }

            if (!Schema::hasTable('models')) {
                throw new \Exception("La tabla 'models' no existe en la base de datos");
            }

            if (!Schema::hasTable('dealerships')) {
                throw new \Exception("La tabla 'dealerships' no existe en la base de datos");
            }

            $make = DB::table('makes')->where('id', $request->make_id)->first();
            if (!$make) {
                return response()->json([
                    'error' => 'La marca seleccionada no existe',
                    'make_id' => $request->make_id
                ], 422);
            }

            $dealership = DB::table('dealerships')->where('id', $request->dealership_id)->first();
            if (!$dealership) {
                return response()->json([
                    'error' => 'El concesionario seleccionado no existe',
                    'dealership_id' => $request->dealership_id
                ], 422);
            }

            $existingCar = DB::table('cars')
                ->where('vin', $request->vin)
                ->where('id', '!=', $id)
                ->first();

            if ($existingCar) {
                return response()->json([
                    'error' => 'Ya existe otro vehículo con este VIN',
                    'vin' => $request->vin
                ], 422);
            }

            $existingModel = DB::table('models')
                ->where('name', $request->modelo)
                ->where('make_id', $request->make_id)
                ->first();

            if ($existingModel) {
                $modelId = $existingModel->id;
            } else {
                $modelColumns = DB::getSchemaBuilder()->getColumnListing('models');
                $modelData = [
                    'name' => $request->modelo,
                    'make_id' => $request->make_id
                ];

                if (in_array('created_at', $modelColumns)) {
                    $modelData['created_at'] = now();
                }
                if (in_array('updated_at', $modelColumns)) {
                    $modelData['updated_at'] = now();
                }

                $modelId = DB::table('models')->insertGetId($modelData);
                Log::info("Nuevo modelo creado con ID: " . $modelId);
            }

            $carColumns = DB::getSchemaBuilder()->getColumnListing('cars');

            $carData = [
                'model_id' => $modelId,
                'dealership_id' => $request->dealership_id,
                'vin' => $request->vin,
                'year' => $request->year,
                'price' => $request->price
            ];

            if (in_array('updated_at', $carColumns)) {
                $carData['updated_at'] = now();
            }

            if ($request->has('mileage') && $request->mileage !== null && $request->mileage !== '' && in_array('mileage', $carColumns)) {
                $carData['mileage'] = $request->mileage;
            }

            if ($request->has('color') && $request->color !== null && $request->color !== '' && in_array('color', $carColumns)) {
                $carData['color'] = $request->color;
            }

            if ($request->has('transmission_type') && $request->transmission_type !== null && $request->transmission_type !== '' && in_array('transmission_type', $carColumns)) {
                $carData['transmission_type'] = $request->transmission_type;
            }

            if ($request->has('fuel_type') && $request->fuel_type !== null && $request->fuel_type !== '' && in_array('fuel_type', $carColumns)) {
                $carData['fuel_type'] = $request->fuel_type;
            }

            if ($request->has('status') && $request->status !== null && $request->status !== '' && in_array('status', $carColumns)) {
                $carData['status'] = $request->status;
            }

            $updated = DB::table('cars')->where('id', $id)->update($carData);
            Log::info("Coche actualizado, ID: " . $id);

            if (!$updated) {
                throw new \Exception('No se pudo actualizar el vehículo');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vehículo actualizado correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al actualizar vehículo: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'error' => 'Error al actualizar el vehículo',
                'message' => $e->getMessage(),
                'trace' => env('APP_DEBUG', false) ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/cars/{id}",
     *     summary="Elimina un vehículo",
     *     tags={"Cars"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del vehículo a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vehículo eliminado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Vehículo eliminado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vehículo no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Vehículo no encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al eliminar el vehículo"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $car = DB::table('cars')->where('id', $id)->first();
            if (!$car) {
                return response()->json([
                    'error' => 'Vehículo no encontrado'
                ], 404);
            }

            Log::info("Solicitud de eliminación del vehículo ID: " . $id);

            if (Schema::hasTable('car_images')) {
                $imagesDeleted = DB::table('car_images')->where('car_id', $id)->delete();
                Log::info("Imágenes eliminadas para el vehículo ID {$id}: {$imagesDeleted}");
            }

            $deleted = DB::table('cars')->where('id', $id)->delete();

            if ($deleted) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Vehículo eliminado correctamente'
                ], 200);
            } else {
                throw new \Exception('No se pudo eliminar el vehículo');
            }
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al eliminar vehículo: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Error al eliminar el vehículo',
                'message' => $e->getMessage(),
                'trace' => env('APP_DEBUG', false) ? $e->getTraceAsString() : null
            ], 500);
        }
    }
}