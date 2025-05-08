<!-- resources/views/dashboard.blade.php -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .content {
            display: flex;
            justify-content: space-between;
        }
        .box {
            padding: 20px;
            background-color: #f4f4f4;
            border: 1px solid #ccc;
            border-radius: 8px;
            width: 30%;
        }
        .box h2 {
            margin-top: 0;
        }
        .logout-btn {
            display: inline-block;
            background-color: #ff5555;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bienvenido, {{ Auth::user()->name }}!</h1>
            <p>¡Has iniciado sesión correctamente!</p>
        </div>

        <div class="content">
            <!-- Box para el rol del usuario -->
            <div class="box">
                <h2>Tu Rol</h2>
                <p>{{ ucfirst(Auth::user()->role) }}</p>
            </div>

            <!-- Box con datos adicionales -->
            <div class="box">
                <h2>Datos del Usuario</h2>
                <ul>
                    <li><strong>Email:</strong> {{ Auth::user()->email }}</li>
                    <li><strong>Fecha de Registro:</strong> {{ Auth::user()->created_at->format('d-m-Y') }}</li>
                </ul>
            </div>

            <!-- Opciones de navegación dependiendo del rol -->
            <div class="box">
                <h2>Opciones</h2>
                @if (Auth::user()->role == 'admin')
                    <p><a href="#">Gestionar Usuarios</a></p>
                    <p><a href="#">Ver Reportes</a></p>
                @elseif (Auth::user()->role == 'employee')
                    <p><a href="#">Ver Tareas</a></p>
                    <p><a href="#">Reportar Problemas</a></p>
                @else
                    <p><a href="#">Ver Productos</a></p>
                    <p><a href="#">Historial de Compras</a></p>
                @endif
            </div>
        </div>

        <!-- Botón de Cerrar Sesión -->
        <div class="footer" style="text-align: center; margin-top: 50px;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">Cerrar sesión</button>
            </form>
        </div>
    </div>
</body>
</html>
