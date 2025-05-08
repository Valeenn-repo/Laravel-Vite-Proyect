<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4">
        <h2 class="text-center">Iniciar Sesión</h2>
        <form id="loginForm">
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
        </form>

        <div class="text-center mt-3">
            <a href="/register" class="btn btn-secondary">Registrarse</a>
        </div>
    </div>

    <script>
        document.getElementById("loginForm").addEventListener("submit", async function(event) {
            event.preventDefault();

            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            const response = await fetch("http://127.0.0.1:8000/api/login", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();

            if (response.ok) {
                localStorage.setItem("token", data.token);
                alert("Inicio de sesión exitoso");
                window.location.href = "/dashboard";
            } else {
                alert("Error: " + data.error);
            }
        });
    </script>
</body>
</html>
