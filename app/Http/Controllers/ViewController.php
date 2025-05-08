<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function showLogin()
    {
        return view('auth.login'); // Cargar la vista de login
    }

    public function showRegister()
    {
        return view('auth.register'); // Cargar la vista de registro
    }

    // Método para la vista de bienvenida o dashboard
    public function showDashboard(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
            'message' => 'Bienvenido al dashboard',
        ]);
    }
}
