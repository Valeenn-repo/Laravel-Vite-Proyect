<template>
  <div class="login-bg">
    <div class="login-container">
      <div class="login-logo">
        <img src="/images/logo.png" alt="Victory Cars" />
      </div>
      <h1>Bienvenido a VCars</h1>
      <p class="login-subtitle">Accede a tu cuenta para gestionar tu concesionario</p>
      <form @submit.prevent="login">
        <div class="input-group">
          <span class="input-icon"><i class="fas fa-envelope"></i></span>
          <input v-model="form.email" type="email" id="email" placeholder="Correo electrónico" required />
        </div>
        <div class="input-group">
          <span class="input-icon"><i class="fas fa-lock"></i></span>
          <input v-model="form.password" type="password" id="password" placeholder="Contraseña" required />
        </div>
        <button type="submit" class="btn-login">Iniciar sesión</button>
        <button type="button" @click="goToRegister" class="register-button">Registrarse</button>
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
import { ref } from "vue";
import axios from "axios";
import { Inertia } from "@inertiajs/inertia";

const form = ref({
  email: "",
  password: "",
});
const errors = ref([]);

const login = async () => {
  try {
    const response = await axios.post("/api/login", form.value);
    const token = response.data.token;
    localStorage.setItem("jwt_token", token);
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    Inertia.visit("/dashboard");
  } catch (error) {
    errors.value = [];
    if (error.response && error.response.data.error) {
      errors.value.push(error.response.data.error);
    } else {
      errors.value.push("Error al intentar iniciar sesión");
    }
  }
};

const goToRegister = () => {
  Inertia.visit("/register");
};
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

.login-bg {
  min-height: 100vh;
  background: linear-gradient(120deg, #232526 0%, #414345 100%);
  display: flex;
  align-items: center;
  justify-content: center;
}

.login-container {
  background: #fff;
  padding: 40px 32px 32px 32px;
  border-radius: 16px;
  box-shadow: 0 8px 32px rgba(44, 62, 80, 0.25);
  max-width: 400px;
  width: 100%;
  text-align: center;
  position: relative;
}

.login-logo img {
  width: 90px;
  margin-bottom: 16px;
}

h1 {
  margin-bottom: 8px;
  color: #232526;
  font-size: 2rem;
  font-weight: 700;
}

.login-subtitle {
  color: #888;
  margin-bottom: 24px;
  font-size: 1rem;
}

.input-group {
  display: flex;
  align-items: center;
  margin-bottom: 18px;
  background: #f3f3f3;
  border-radius: 6px;
  padding: 0 10px;
  border: 1px solid #e0e0e0;
}

.input-icon {
  color: #888;
  font-size: 1.1rem;
  margin-right: 8px;
}

input[type="email"],
input[type="password"] {
  border: none;
  background: transparent;
  outline: none;
  padding: 12px 0;
  width: 100%;
  font-size: 1rem;
  color: #232526;
}

input[type="email"]:focus,
input[type="password"]:focus {
  background: #e9ecef;
}

.btn-login {
  width: 100%;
  padding: 12px;
  background: linear-gradient(90deg, #ffb347 0%, #ffcc33 100%);
  color: #232526;
  border: none;
  border-radius: 6px;
  font-size: 1.1rem;
  font-weight: 600;
  margin-top: 10px;
  margin-bottom: 8px;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-login:hover {
  background: linear-gradient(90deg, #ffcc33 0%, #ffb347 100%);
}

.register-button {
  width: 100%;
  padding: 10px;
  background: #232526;
  color: #fff;
  border: none;
  border-radius: 6px;
  font-size: 1rem;
  margin-bottom: 10px;
  cursor: pointer;
  transition: background 0.2s;
}

.register-button:hover {
  background: #414345;
}

.error-messages {
  margin-top: 15px;
  color: #dc3545;
  text-align: left;
}

.error-messages ul {
  list-style-type: none;
  padding: 0;
}

.error-messages li {
  margin-bottom: 5px;
}
</style>