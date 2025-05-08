<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ViewController;
use Inertia\Inertia;
use App\Http\Controllers\CarDataController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\DealershipsController;
use App\Http\Controllers\CarImagesController;



Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [ViewController::class, 'showDashboard']);
    
    //Route::get('/cars', [CarController::class, 'index']);
    //Route::get('/cars/{id}', [CarController::class, 'show']);
    
});

// Public car-related routes
Route::get('/cars', [CarController::class, 'index']);
Route::get('/cars/{id}', [CarController::class, 'show']);
Route::delete('/cars/{id}', [CarController::class, 'destroy']);
Route::put('/cars/{id}', [CarController::class, 'update']);
Route::get('/car-data', [CarDataController::class, 'grouped']);


Route::get('/dealerships', [DealershipsController::class, 'index']);
Route::post('/cars', [CarController::class, 'store']);

// Rutas para la gestión de imágenes de vehículos
Route::post('/car-images', [CarImagesController::class, 'store']);
Route::put('/car-images/{id}', [CarImagesController::class, 'update']);
Route::delete('/car-images/{id}', [CarImagesController::class, 'destroy']);
