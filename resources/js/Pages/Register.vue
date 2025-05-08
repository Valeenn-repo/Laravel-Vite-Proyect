<template>
  <div class="register-bg">
    <div class="register-box">
      <div class="register-logo">
        <img src="/images/logo.png" alt="Victory Cars" />
      </div>
      <h1>Crear cuenta en VCars</h1>
      <p class="register-subtitle">Completa tus datos para registrarte</p>
      <div v-if="loadingUser">
        Cargando...
      </div>
      <div v-else-if="accessDenied">
        <div class="error-messages">
          Acceso denegado. Solo administradores pueden acceder a este registro.
        </div>
      </div>
      <form v-else @submit.prevent="register">
        <div class="input-group">
          <label for="name">Nombre:</label>
          <input v-model="form.name" type="text" id="name" required autocomplete="name" />
        </div>
        <div class="input-group">
          <label for="email">Email:</label>
          <input v-model="form.email" type="email" id="email" required autocomplete="email" />
        </div>
        <div class="input-group">
          <label for="password">Contraseña:</label>
          <input v-model="form.password" type="password" id="password" required autocomplete="new-password" />
        </div>
        <div class="input-group">
          <label for="password_confirmation">Confirmar Contraseña:</label>
          <input v-model="form.password_confirmation" type="password" id="password_confirmation" required autocomplete="new-password" />
        </div>
        <div class="input-group" v-if="isAdminRoute">
          <label for="role">Rol:</label>
          <select v-model="form.role" id="role" required>
            <option value="" disabled>Selecciona un rol</option>
            <option value="customer">Cliente</option>
            <option value="employee">Empleado</option>
            <option value="admin">Administrador</option>
          </select>
        </div>
        <!-- Si no es admin, el campo de rol no se muestra -->
        <button type="submit" class="register-button" :disabled="loading">
          <span v-if="loading" class="spinner"></span>
          <span v-else>Registrarse</span>
        </button>
        <button type="button" @click="goToLogin" class="login-button" :disabled="loading">Iniciar Sesión</button>
        <div v-if="errors.length" class="error-messages">
          <ul>
            <li v-for="error in errors" :key="error">{{ error }}</li>
          </ul>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import axios from "axios";
import { Inertia } from "@inertiajs/inertia";
import Swal from "sweetalert2";

const isAdminRoute = computed(() => window.location.pathname.includes("/admin"));

const form = ref({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
  role: ""
});

const user = ref(null);
const loadingUser = ref(true);
const errors = ref([]);
const loading = ref(false);
const accessDenied = ref(false);

onMounted(async () => {
  if (!isAdminRoute.value) {
    form.value.role = "customer";
    loadingUser.value = false; // <-- Añade esta línea
    return;
  }
  // Si es ruta admin, comprobar usuario
  try {
    const token = localStorage.getItem("jwt_token");
    if (!token) {
      accessDenied.value = true;
      return;
    }
    const response = await axios.get("http://localhost:8000/api/me", {
      headers: { Authorization: `Bearer ${token}` }
    });
    user.value = response.data;
    if (!user.value || user.value.role !== "admin") {
      accessDenied.value = true;
    }
  } catch (e) {
    accessDenied.value = true;
  } finally {
    loadingUser.value = false;
  }
});

const validateForm = () => {
  errors.value = [];
  if (!form.value.name.trim()) errors.value.push("El nombre es obligatorio.");
  if (!form.value.email.trim()) errors.value.push("El email es obligatorio.");
  if (!form.value.password) errors.value.push("La contraseña es obligatoria.");
  if (form.value.password !== form.value.password_confirmation) errors.value.push("Las contraseñas no coinciden.");
  if (isAdminRoute.value && !form.value.role) errors.value.push("Debes seleccionar un rol.");
  return errors.value.length === 0;
};

const register = async () => {
  if (!validateForm()) return;
  loading.value = true;
  try {
    const response = await axios.post("http://localhost:8000/api/register", form.value);
    await Swal.fire({
      icon: "success",
      title: "¡Registro exitoso!",
      text: "Usuario registrado correctamente. Por favor, inicia sesión.",
      confirmButtonText: "Aceptar"
    });
    Inertia.visit("/login");
  } catch (error) {
    errors.value = [];
    if (error.response?.data?.errors) {
      Object.values(error.response.data.errors).forEach((err) => errors.value.push(err[0]));
    } else if (error.response?.data?.message) {
      errors.value.push(error.response.data.message);
    } else {
      errors.value.push("Error al intentar registrarse");
    }
    await Swal.fire({
      icon: "error",
      title: "Error",
      text: errors.value.join("\n"),
      confirmButtonText: "Aceptar"
    });
  } finally {
    loading.value = false;
  }
};

const goToLogin = () => {
  Inertia.visit("/login");
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

.register-bg {
  min-height: 100vh;
  background: linear-gradient(120deg, #232526 0%, #414345 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Montserrat', Arial, sans-serif;
}

.register-box {
  background: #fff;
  padding: 38px 32px 32px 32px;
  border-radius: 18px;
  box-shadow: 0 8px 32px rgba(44, 62, 80, 0.18);
  max-width: 400px;
  width: 100%;
  text-align: center;
  position: relative;
}

.register-logo img {
  width: 80px;
  margin-bottom: 12px;
}

h1 {
  margin-bottom: 8px;
  color: #232526;
  font-size: 1.7rem;
  font-weight: 700;
}

.register-subtitle {
  color: #888;
  margin-bottom: 22px;
  font-size: 1rem;
}

.input-group {
  margin-bottom: 16px;
  text-align: left;
}

.input-group label {
  display: block;
  margin-bottom: 5px;
  font-size: 14px;
  color: #555;
  font-weight: 600;
}

.input-group input,
.input-group select {
  width: 100%;
  padding: 11px;
  border: 1px solid #e0e0e0;
  border-radius: 7px;
  font-size: 15px;
  background: #f7f7f7;
  transition: border 0.2s;
}

.input-group input:focus,
.input-group select:focus {
  border: 1.5px solid #ffcc33;
  outline: none;
  background: #fffbe6;
}

button {
  width: 100%;
  padding: 12px;
  border: none;
  border-radius: 7px;
  font-size: 1.08rem;
  cursor: pointer;
  margin-top: 10px;
  font-family: 'Montserrat', Arial, sans-serif;
  font-weight: 600;
  transition: background 0.2s, color 0.2s;
}

.register-button {
  background: linear-gradient(90deg, #ffb347 0%, #ffcc33 100%);
  color: #232526;
  margin-bottom: 8px;
}

.register-button:hover {
  background: linear-gradient(90deg, #ffcc33 0%, #ffb347 100%);
  color: #232526;
}

.login-button {
  background-color: #232526;
  color: #fff;
}

.login-button:hover {
  background-color: #414345;
}

.error-messages {
  margin-top: 15px;
  color: #dc3545;
  background: #f8d7da;
  padding: 10px;
  border-radius: 5px;
  font-size: 14px;
  text-align: left;
}

.spinner {
  border: 3px solid #f3f3f3;
  border-top: 3px solid #ffcc33;
  border-radius: 50%;
  width: 18px;
  height: 18px;
  display: inline-block;
  animation: spin 1s linear infinite;
  margin-right: 8px;
  vertical-align: middle;
}
@keyframes spin {
  0% { transform: rotate(0deg);}
  100% { transform: rotate(360deg);}
}
</style>
