<template>
  <div id="app">
    <h1>Mi Aplicación Vue</h1>
    <router-view></router-view> <!-- Aquí se renderizan las rutas -->
  </div>
</template>

<script>
export default {
  name: 'App',
};
</script>

<style scoped>
/* Estilos generales */
#app {
  font-family: 'Arial', sans-serif;
  text-align: center;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #ece9e6, #ffffff);
  color: #333;
}

/* Título principal */
h1 {
  font-size: 2rem;
  font-weight: bold;
  margin-bottom: 20px;
  color: #007bff;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
}

/* Contenedor de rutas */
router-view {
  width: 100%;
  max-width: 600px;
  padding: 20px;
  background: white;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
</style>
