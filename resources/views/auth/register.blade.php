<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4">
        <h2 class="text-center">Registro</h2>
        <form id="registerForm">
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                <input type="password" id="password_confirmation" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rol</label>
                <select id="role" class="form-control" required>
                    <option value="customer">Cliente</option>
                    <option value="employee">Empleado</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Registrar</button>
        </form>

        <div class="text-center mt-3">
            <a href="/login" class="btn btn-secondary">Volver al Login</a>
        </div>
    </div>

    <script>
        document.getElementById("registerForm").addEventListener("submit", async function(event) {
            event.preventDefault();

            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;
            const password_confirmation = document.getElementById("password_confirmation").value;
            const role = document.getElementById("role").value;

            const response = await fetch("http://127.0.0.1:8000/api/register", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email, password, password_confirmation, role })
            });

            const data = await response.json();

            if (response.ok) {
                alert("Registro exitoso. Ahora puedes iniciar sesión.");
                window.location.href = "/login";
            } else {
                alert("Error: " + JSON.stringify(data.errors));
            }
        });
    </script>
</body>
</html>
