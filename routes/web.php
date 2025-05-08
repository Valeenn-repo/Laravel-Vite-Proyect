<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CarController;
use Illuminate\Support\Facades\Auth;


Route::group(['prefix' => 'api-docs'], function () {
    Route::get('/', function () {
        return view('l5-swagger.index');
    });
});

Route::get('/login', function () {
    return Inertia::render('Login');
})->name('login');

Route::get('/register', function () {
    return Inertia::render('Register');
})->name('register');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('/compramos-tu-coche', function () {
    return Inertia::render('CompramosTuCoche');
})->name('compramos-tu-coche');

Route::get('/financiacion', function () {
    return Inertia::render('Financiacion');
})->name('financiacion');

Route::get('/contacto', function () {
    return Inertia::render('Contacto');
})->name('contacto');

Route::get('/admin/register', function () {
    return Inertia::render('Register');
})->name('admin.register');

Route::get('/vehiculos', function () {
    return Inertia::render('Vehiculos');
})->name('vehiculos');

Route::get('/cars', [CarController::class, 'index']);

Route::get('/cars/{id}', function ($id) {
    // Simplemente le pasamos el id al componente Vue
    return Inertia::render('CarData', [
        'id' => $id,
    ]);
})->name('cars.show');

Route::get('/vehiculos/create', function () {
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        abort(403, 'No tienes permiso para acceder a esta página.');
    }
    return Inertia::render('AddVehicle');
})->name('vehiculos.create');

Route::get('/vehiculos/edit', function () {
    // Solo permite acceso si el usuario está autenticado y es admin
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        abort(403, 'No tienes permiso para acceder a esta página.');
    }
    return Inertia::render('DeleteVehicle');
})->name('vehiculos.delete');

Route::get('/vehiculos/edit/{id}', function ($id) {
    return Inertia::render('Edit',[
        'id' => $id,
    ]);
})->name('vehiculos.edit');