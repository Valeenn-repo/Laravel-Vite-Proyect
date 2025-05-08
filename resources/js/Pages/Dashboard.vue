<template>
  <div class="dashboard-container">
    <!-- Encabezado / Barra de navegación -->
    <header class="dashboard-header">
      <div class="navbar-wrapper">
        <nav class="navbar">
          <div class="logo">
            <img src="/images/logo.png" alt="Victory Cars" />
          </div>
          <span class="menu-icon" @click="toggleMenu"><i class="fas fa-bars"></i></span>
          <ul class="nav-links" :class="{ 'show': showMenu }">
            <li><Link href="/dashboard">Inicio</Link></li>
            <li><Link href="/compramos-tu-coche">Compramos tu coche</Link></li>
            <li><Link href="/vehiculos">Vehículos</Link></li>
            <li><Link href="/financiacion">Financiación</Link></li>
            <li><Link href="/contacto">Contacto</Link></li>
            <li v-if="userLoading"><i class="fas fa-spinner fa-spin"></i></li>
            <li v-else-if="!user"><Link href="/login" class="login-btn">Iniciar sesión</Link></li>
            <li v-else class="user-info" @click="showUserMenu = !showUserMenu" style="position: relative;">
              <div style="cursor:pointer;">
                <i class="fas fa-user"></i> {{ user.name || user.email }}
                <i class="fas fa-chevron-down" style="margin-left:6px;font-size:0.9em;"></i>
              </div>
              <transition name="fade">
                <div v-if="showUserMenu" class="user-dropdown">
                  <div class="dropdown-arrow"></div>
                  <button v-if="user && user.role === 'admin'" @click="goToAddVehicle" class="logout-btn">
                    <i class="fas fa-plus"></i> Añadir vehículo
                  </button>
                  <button @click.stop="logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                  </button>
                </div>
              </transition>
            </li>
          </ul>
        </nav>
      </div>
      <!-- Sección Hero -->
      <div class="hero-section">
        <div class="hero-content">
          <h1>Bienvenido a VCars</h1>
          <p>Encuentra tu próximo coche con la mejor financiación</p>
          <button @click="buscarVehiculos">Ver Ofertas</button>
        </div>
      </div>
    </header>

    <!-- Contenido principal -->
    <main class="dashboard-main">
      <!-- Buscador de coches -->
      <div class="search-section">
        <h2>Busca tu coche</h2>
        <form @submit.prevent="buscarVehiculos">
          <select v-model="selectedMarca">
            <option value="" disabled selected>Marca</option>
            <option v-for="marca in marcas" :key="marca.id" :value="marca.id">{{ marca.name }}</option>
          </select>
          <select v-model="selectedModelo" :disabled="!selectedMarca">
            <option value="" disabled selected>Modelo</option>
            <option v-for="modelo in getModelosByMarca" :key="modelo.id" :value="modelo.id">{{ modelo.name }}</option>
          </select>
          <button type="submit">Buscar</button>
        </form>
      </div>

      <!-- Ofertas destacadas -->
      <div class="offers-section">
        <h2>Ofertas Destacadas</h2>
        <div class="cards">
          <div class="card">
            <img src="/images/fiat-500.webp" alt="Fiat 500" />
            <h3>Fiat 500</h3>
            <p>Desde 150€/mes</p>
          </div>
          <div class="card">
            <img src="/images/polo-gti-7.jpg" alt="Volkswagen Polo" />
            <h3>Volkswagen Polo</h3>
            <p>Desde 190€/mes</p>
          </div>
        </div>
      </div>

      <!-- Últimas Noticias o Blog -->
      <div class="news-section">
        <h2>Últimas Noticias</h2>
        <div class="news">
          <div class="news-item">
            <h3>Nuevo modelo de coche disponible</h3>
            <p>Descubre el nuevo modelo de coche que hemos añadido a nuestro inventario.</p>
            <a href="#">Leer más</a>
          </div>
          <div class="news-item">
            <h3>Consejos para el mantenimiento del coche</h3>
            <p>Aprende cómo mantener tu coche en perfectas condiciones con estos consejos.</p>
            <a href="#">Leer más</a>
          </div>
        </div>
      </div>

      <!-- Características Destacadas -->
      <div class="features-section">
        <h2>Características Destacadas</h2>
        <div class="features">
          <div class="feature">
            <i class="fas fa-money-bill-wave"></i>
            <h3>Financiación Flexible</h3>
            <p>Ofrecemos opciones de financiación adaptadas a tus necesidades.</p>
          </div>
          <div class="feature">
            <i class="fas fa-shield-alt"></i>
            <h3>Garantía Extendida</h3>
            <p>Todos nuestros coches vienen con una garantía extendida para tu tranquilidad.</p>
          </div>
        </div>
      </div>

      <!-- Galería de Vehículos -->
      <div class="gallery-section">
        <h2>Galería de Vehículos</h2>
        <div class="gallery">
          <img src="/images/ford_mustang_black" alt="Coche 1" />
          <img src="/images/mercedes-benz_clasec_silver" alt="Coche 2" />
          <img src="/images/volkswagen_golf_blue.jpg" alt="Coche 3" />
        </div>
      </div>

      <!-- Llamada a la Acción -->
      <div class="cta-section">
        <h2>¿Listo para encontrar tu coche ideal?</h2>
        <button @click="solicitarCotizacion">Solicitar una Cotización</button>
      </div>

      <!-- Redes Sociales -->
      <div class="social-section">
        <h2>Síguenos en Redes Sociales</h2>
        <div class="social-icons">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>

      <!-- Mapa de Ubicación -->
      <div class="map-section">
        <h2>Encuéntranos</h2>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835434509374!2d144.9537353153167!3d-37.81627927975171!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f11fd81%3A0xf0727f7b0b0b0b0b!2sVictoria%20State%20Library!5e0!3m2!1sen!2sau!4v1633023456789!5m2!1sen!2sau" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
      </div>

      <!-- Preguntas Frecuentes -->
      <div class="faq-section">
        <h2>Preguntas Frecuentes</h2>
        <div class="faq">
          <div class="faq-item">
            <h3>¿Cuáles son los requisitos para financiar un coche?</h3>
            <p>Los requisitos varían, pero generalmente incluyen una identificación válida, comprobante de ingresos y un buen historial crediticio.</p>
          </div>
          <div class="faq-item">
            <h3>¿Ofrecen garantía en los coches usados?</h3>
            <p>Sí, todos nuestros coches usados vienen con una garantía extendida.</p>
          </div>
        </div>
      </div>


      <!-- Comparador de Vehículos -->
      <div class="comparison-section">
        <h2>Compara Vehículos</h2>
        <div class="comparison">
          <div class="comparison-item">
            <h3>Fiat 500</h3>
            <p>Precio: 150€/mes</p>
            <p>Consumo: 5.1L/100km</p>
            <p>Potencia: 69 CV</p>
          </div>
          <div class="comparison-item">
            <h3>Volkswagen Polo</h3>
            <p>Precio: 190€/mes</p>
            <p>Consumo: 4.8L/100km</p>
            <p>Potencia: 95 CV</p>
          </div>
        </div>
      </div>

      <!-- Consejos de Compra -->
      <div class="tips-section">
        <h2>Consejos para Comprar tu Coche</h2>
        <div class="tips">
          <div class="tip">
            <h3>Define tu presupuesto</h3>
            <p>Establece cuánto estás dispuesto a gastar para evitar sorpresas.</p>
          </div>
          <div class="tip">
            <h3>Prueba el vehículo</h3>
            <p>Agenda una prueba de manejo para asegurarte de que el coche cumple tus expectativas.</p>
          </div>
        </div>
      </div>

      <!-- Estadísticas de la Empresa -->
      <div class="stats-section">
        <h2>Nuestras Estadísticas</h2>
        <div class="stats">
          <div class="stat">
            <h3>10,000+</h3>
            <p>Clientes Satisfechos</p>
          </div>
          <div class="stat">
            <h3>500+</h3>
            <p>Vehículos Vendidos</p>
          </div>
          <div class="stat">
            <h3>15</h3>
            <p>Años de Experiencia</p>
          </div>
        </div>
      </div>

      <!-- Promociones Especiales -->
      <div class="promotions-section">
        <h2>Promociones Especiales</h2>
        <div class="promotions">
          <div class="promotion">
            <h3>Descuento de Temporada</h3>
            <p>10% de descuento en todos los vehículos este mes.</p>
          </div>
          <div class="promotion">
            <h3>Financiación Sin Intereses</h3>
            <p>Financia tu coche a 12 meses sin intereses.</p>
          </div>
        </div>
      </div>

      

    </main>

    <!-- Pie de página -->
    <footer class="dashboard-footer">
      <p>© 2023 VCars. Todos los derechos reservados.</p>
    </footer>
  </div>
</template>

<script>
import axios from 'axios';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

export default {
  components: { Link },
  name: "Dashboard",
  data() {
    return {
      message: "",
      marcas: [],
      selectedMarca: "",
      selectedModelo: "",
      user: null,
      userLoading: true,
      showUserMenu: false,
      showMenu: false,
      contacto: { nombre: "", email: "", mensaje: "" }
    };
  },
  mounted() {
    this.loadDashboard();
    this.loadMarcas();
    this.fetchUser();
    document.addEventListener('click', this.closeMenu);
  },
  beforeUnmount() {
    document.removeEventListener('click', this.closeMenu);
  },
  computed: {
    getModelosByMarca() {
      const marca = this.marcas.find(m => m.id === this.selectedMarca);
      return marca ? marca.models : [];
    },
  },
  methods: {
    async logout() {
      const token = localStorage.getItem("jwt_token");
      if (token) {
        try {
          await axios.post("http://localhost:8000/api/logout", {}, {
            headers: { Authorization: `Bearer ${token}` }
          });
        } catch (error) {
          console.error("Error al cerrar sesión", error);
        }
        localStorage.removeItem("jwt_token");
        this.user = null;
      }
    },
    async loadDashboard() {
      try {
        const token = localStorage.getItem("jwt_token");
        const response = await axios.get("http://localhost:8000/api/dashboard", {
          headers: { Authorization: `Bearer ${token}` },
        });
        this.message = response.data.message;
      } catch (error) {
        console.error("Error al cargar el dashboard", error);
      }
    },
    async loadMarcas() {
      try {
        const response = await axios.get("http://localhost:8000/api/car-data");
        this.marcas = response.data;
      } catch (error) {
        console.error("Error al cargar marcas y modelos", error);
      }
    },
    async fetchUser() {
      this.userLoading = true;
      const token = localStorage.getItem("jwt_token");
      if (token) {
        try {
          const response = await axios.get("http://localhost:8000/api/me", {
            headers: { Authorization: `Bearer ${token}` }
          });
          this.user = response.data;
        } catch (error) {
          this.user = null;
        }
      } else {
        this.user = null;
      }
      this.userLoading = false;
    },
    buscarVehiculos() {
      const params = {};
      if (this.selectedMarca) params.marca = this.selectedMarca;
      if (this.selectedModelo) params.modelo = this.selectedModelo;
      try {
        Inertia.get('/vehiculos', params);
      } catch (error) {
        console.error("Error al navegar con Inertia:", error);
        const queryParams = new URLSearchParams();
        if (params.marca) queryParams.append('marca', params.marca);
        if (params.modelo) queryParams.append('modelo', params.modelo);
        window.location.href = `/vehiculos?${queryParams.toString()}`;
      }
    },
    toggleMenu() {
      this.showMenu = !this.showMenu;
    },
    closeMenu(e) {
      if (!this.$el.querySelector('.user-dropdown')?.contains(e.target) && !this.$el.querySelector('.user-info').contains(e.target)) {
        this.showUserMenu = false;
      }
    },
    solicitarCotizacion() {
      alert("Funcionalidad de cotización en desarrollo.");
    },
    async enviarContacto() {
      try {
        await axios.post("http://localhost:8000/api/contacto", this.contacto);
        alert("Mensaje enviado con éxito.");
        this.contacto = { nombre: "", email: "", mensaje: "" };
      } catch (error) {
        console.error("Error al enviar el mensaje", error);
        alert("Hubo un error al enviar el mensaje. Inténtalo de nuevo.");
      }
    },
    goToAddVehicle() {
      this.$inertia.visit('/vehiculos/edit');
    }
  }
};
</script>

<style scoped>
/* Estilos para el contenedor principal */
a {
    color: inherit; /* Mantiene el color del texto circundante */
    text-decoration: none; /* Elimina el subrayado */
}
/* Estilos para el dropdown del usuario */
.user-dropdown {
  position: absolute;
  top: 180%;
  left: 50%;
  transform: translateX(-50%);
  background: #fff;
  border: 1px solid #eee;
  border-radius: 10px;
  box-shadow: 0 8px 24px rgba(44,62,80,0.18), 0 1.5px 6px rgba(0,0,0,0.07);
  padding: 18px 0 10px 0;
  min-width: 170px;
  z-index: 100;
  animation: dropdownIn 0.22s cubic-bezier(.4,0,.2,1);
}

@keyframes dropdownIn {
  0% { opacity: 0; transform: translateY(-10px) scale(0.98); }
  100% { opacity: 1; transform: translateY(0) scale(1); }
}

.dropdown-arrow {
  position: absolute;
  top: -10px;
  left: 50%;
  transform: translateX(-50%);
  width: 18px;
  height: 18px;
  background: #fff;
  border-left: 1px solid #eee;
  border-top: 1px solid #eee;
  border-radius: 3px 0 0 0;
  box-shadow: -2px -2px 6px rgba(44,62,80,0.08);
  transform: translateX(-50%) rotate(45deg);
  z-index: 101;
}

.user-dropdown .logout-btn {
  width: 100%;
  background: none;
  color: #232526;
  border: none;
  border-radius: 0 0 10px 10px;
  font-weight: 600;
  padding: 10px 18px 8px 18px;
  text-align: left;
  cursor: pointer;
  transition: background 0.2s, color 0.2s;
  font-size: 1rem;
  display: flex;
  align-items: center;
  gap: 8px;
}

.user-dropdown .logout-btn:hover {
  background: #ffcc00;
  color: #232526;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.18s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

/* Contenedor general */
html, body {
  margin: 0;
  padding: 0;
  background-color: #000;
  height: 100%;
}

.dashboard-container {
  font-family: Arial, sans-serif;
  color: #333;
  background: #fff;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

/* Encabezado */
.dashboard-header {
  position: relative;
  background-color: #000;
  width: 100%;
  margin: 0;
  padding: 0;
}

/* Barra de navegación fija */
.navbar-wrapper {
  position: sticky;
  top: 0;
  z-index: 100;
  background: #000;
}

.navbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 2rem;
}

.navbar .logo img {
  height: 60px;
}

.navbar .nav-links {
  list-style: none;
  display: flex;
  margin: 0;
  padding: 0;
}

.navbar .nav-links li {
  margin-left: 3rem;
  color: #fff;
  cursor: pointer;
  transition: color 0.3s;
}

.navbar .nav-links li:hover {
  color: #ffcc00;
}

.menu-icon {
  display: none;
  color: #fff;
  font-size: 1.5rem;
  cursor: pointer;
}

/* Estilos responsivos para el menú hamburguesa */
@media (max-width: 768px) {
  .menu-icon {
    display: block;
  }
  .nav-links {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    background: #000;
    flex-direction: column;
    padding: 1rem 0;
  }
  .nav-links.show {
    display: flex;
  }
  .nav-links li {
    margin: 0.5rem 0;
    text-align: center;
  }
}

@media (min-width: 769px) {
  .nav-links {
    display: flex;
  }
}

/* Sección Hero con superposición */
.hero-section {
  position: relative;
  background: url("hero-car.jpg") center center / cover no-repeat;
  color: #fff;
  text-align: center;
  padding: 4rem 2rem;
}

.hero-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
}

.hero-content {
  position: relative;
  z-index: 2;
}

.hero-section h1 {
  font-size: 2.5rem;
  margin-bottom: 1rem;
}

.hero-section p {
  font-size: 1.2rem;
  margin-bottom: 2rem;
}

.hero-section button {
  padding: 0.8rem 1.2rem;
  border: none;
  background-color: #ffcc00;
  cursor: pointer;
  font-size: 1rem;
  border-radius: 4px;
  transition: background-color 0.3s;
}

.hero-section button:hover {
  background-color: #ffbb00;
}

/* Contenido principal */
.dashboard-main {
  flex: 1;
  padding: 2rem;
}

/* Sección de búsqueda */
.search-section {
  text-align: center;
  margin-bottom: 3rem;
}

.search-section h2 {
  margin-bottom: 1rem;
  font-size: 1.8rem;
}

.search-section form {
  display: inline-flex;
  gap: 1rem;
  flex-wrap: wrap;
  align-items: center;
  justify-content: center;
  margin-top: 1rem;
}

.search-section select,
.search-section button {
  padding: 0.5rem 1rem;
  border-radius: 4px;
  border: 1px solid #ccc;
  font-size: 1rem;
  outline: none;
}

.search-section button {
  background-color: #000;
  color: #fff;
  cursor: pointer;
  border: none;
  transition: background-color 0.3s;
}

.search-section button:hover {
  background-color: #333;
}

/* Ofertas destacadas */
.offers-section h2 {
  font-size: 1.8rem;
  margin-bottom: 1rem;
  text-align: center;
}

.offers-section .cards {
  display: flex;
  gap: 2rem;
  justify-content: center;
  flex-wrap: wrap;
}

.offers-section .card {
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 8px;
  width: 650px;
  text-align: center;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: transform 0.3s, box-shadow 0.3s;
}

.offers-section .card:hover {
  transform: translateY(-3px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.offers-section .card img {
  width: 100%;
  height: auto;
  display: block;
}

.offers-section .card h3 {
  margin: 0.5rem 0;
  font-size: 1.2rem;
  color: #333;
}

.offers-section .card p {
  margin-bottom: 1rem;
  color: #666;
}

/* Testimonios de Clientes */
.testimonials-section {
  text-align: center;
  margin-bottom: 3rem;
}

.testimonials-section h2 {
  font-size: 1.8rem;
  margin-bottom: 1rem;
}

.testimonials {
  display: flex;
  gap: 2rem;
  justify-content: center;
  flex-wrap: wrap;
}

.testimonial {
  background-color: #f8f8f8;
  border-radius: 8px;
  padding: 1rem;
  width: 250px;
  text-align: center;
}

.testimonial img {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  margin-bottom: 1rem;
}

.testimonial p {
  font-style: italic;
  margin-bottom: 0.5rem;
}

.testimonial h4 {
  font-size: 1.1rem;
  color: #333;
}

/* Últimas Noticias o Blog */
.news-section {
  text-align: center;
  margin-bottom: 3rem;
}

.news-section h2 {
  font-size: 1.8rem;
  margin-bottom: 1rem;
}

.news {
  display: flex;
  gap: 2rem;
  justify-content: center;
  flex-wrap: wrap;
}

.news-item {
  background-color: #f8f8f8;
  border-radius: 8px;
  padding: 1rem;
  width: 250px;
  text-align: left;
}

.news-item h3 {
  font-size: 1.2rem;
  margin-bottom: 0.5rem;
}

.news-item p {
  margin-bottom: 0.5rem;
}

.news-item a {
  color: #ffcc00;
  text-decoration: none;
}

.news-item a:hover {
  text-decoration: underline;
}

/* Características Destacadas */
.features-section {
  text-align: center;
  margin-bottom: 3rem;
}

.features-section h2 {
  font-size: 1.8rem;
  margin-bottom: 1rem;
}

.features {
  display: flex;
  gap: 2rem;
  justify-content: center;
  flex-wrap: wrap;
}

.feature {
  background-color: #f8f8f8;
  border-radius: 8px;
  padding: 1rem;
  width: 250px;
  text-align: center;
}

.feature i {
  font-size: 2rem;
  color: #ffcc00;
  margin-bottom: 0.5rem;
}

.feature h3 {
  font-size: 1.2rem;
  margin-bottom: 0.5rem;
}

.feature p {
  color: #666;
}

/* Galería de Vehículos */
.gallery-section {
  text-align: center;
  margin-bottom: 3rem;
}

.gallery-section h2 {
  font-size: 1.8rem;
  margin-bottom: 1rem;
}

.gallery {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

.gallery img {
  width: 200px;
  height: auto;
  border-radius: 8px;
}

/* Llamada a la Acción */
.cta-section {
  text-align: center;
  margin-bottom: 3rem;
}

.cta-section h2 {
  font-size: 1.8rem;
  margin-bottom: 1rem;
}

.cta-section button {
  padding: 0.8rem 1.2rem;
  border: none;
  background-color: #ffcc00;
  cursor: pointer;
  font-size: 1rem;
  border-radius: 4px;
  transition: background-color 0.3s;
}

.cta-section button:hover {
  background-color: #ffbb00;
}

/* Redes Sociales */
.social-section {
  text-align: center;
  margin-bottom: 3rem;
}

.social-section h2 {
  font-size: 1.8rem;
  margin-bottom: 1rem;
}

.social-icons {
  display: flex;
  gap: 1rem;
  justify-content: center;
}

.social-icons a {
  color: #333;
  font-size: 1.5rem;
  transition: color 0.3s;
}

.social-icons a:hover {
  color: #ffcc00;
}

/* Mapa de Ubicación */
.map-section {
  text-align: center;
  margin-bottom: 3rem;
}

.map-section h2 {
  font-size: 1.8rem;
  margin-bottom: 1rem;
}

.map-section iframe {
  width: 100%;
  max-width: 600px;
  height: 450px;
  border: 0;
}

/* Preguntas Frecuentes */
.faq-section {
  text-align: center;
  margin-bottom: 3rem;
}

.faq-section h2 {
  font-size: 1.8rem;
  margin-bottom: 1rem;
}

.faq {
  display: flex;
  gap: 2rem;
  justify-content: center;
  flex-wrap: wrap;
}

.faq-item {
  background-color: #f8f8f8;
  border-radius: 8px;
  padding: 1rem;
  width: 250px;
  text-align: left;
}

.faq-item h3 {
  font-size: 1.2rem;
  margin-bottom: 0.5rem;
}

.faq-item p {
  color: #666;
}

/* Certificaciones o Premios */
.awards-section {
  text-align: center;
  margin-bottom: 3rem;
}

.awards-section h2 {
  font-size: 1.8rem;
  margin-bottom: 1rem;
}

.awards {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

.awards img {
  width: 150px;
  height: auto;
}

/* Formulario de Contacto */
.contact-section {
  text-align: center;
  margin-bottom: 3rem;
}

.contact-section h2 {
  font-size: 1.8rem;
  margin-bottom: 1rem;
}

.contact-section form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  max-width: 400px;
  margin: 0 auto;
}

.contact-section input,
.contact-section textarea {
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 1rem;
}

.contact-section button {
  padding: 0.8rem 1.2rem;
  border: none;
  background-color: #ffcc00;
  cursor: pointer;
  font-size: 1rem;
  border-radius: 4px;
  transition: background-color 0.3s;
}

.contact-section button:hover {
  background-color: #ffbb00;
}

/* Comparador de Vehículos */
.comparison-section {
  text-align: center;
  margin-bottom: 3rem;
}

.comparison-section h2 {
  font-size: 1.8rem;
  margin-bottom: 1rem;
}

.comparison {
  display: flex;
  gap: 2rem;
  justify-content: center;
  flex-wrap: wrap;
}

.comparison-item {
  background-color: #f8f8f8;
  border-radius: 8px;
  padding: 1rem;
  width: 250px;
  text-align: left;
}

.comparison-item h3 {
  font-size: 1.2rem;
  margin-bottom: 0.5rem;
}

.comparison-item p {
  color: #666;
  margin: 0.3rem 0;
}

/* Consejos de Compra */
.tips-section {
  text-align: center;
  margin-bottom: 3rem;
}

.tips-section h2 {
  font-size: 1.8rem;
  margin-bottom: 1rem;
}

.tips {
  display: flex;
  gap: 2rem;
  justify-content: center;
  flex-wrap: wrap;
}

.tip {
  background-color: #f8f8f8;
  border-radius: 8px;
  padding: 1rem;
  width: 250px;
  text-align: left;
}

.tip h3 {
  font-size: 1.2rem;
  margin-bottom: 0.5rem;
}

.tip p {
  color: #666;
}

/* Estadísticas de la Empresa */
.stats-section {
  text-align: center;
  margin-bottom: 3rem;
}

.stats-section h2 {
  font-size: 1.8rem;
  margin-bottom: 1rem;
}

.stats {
  display: flex;
  gap: 2rem;
  justify-content: center;
  flex-wrap: wrap;
}

.stat {
  background-color: #f8f8f8;
  border-radius: 8px;
  padding: 1rem;
  width: 200px;
  text-align: center;
}

.stat h3 {
  font-size: 1.5rem;
  color: #ffcc00;
  margin-bottom: 0.5rem;
}

.stat p {
  color: #666;
}

/* Promociones Especiales */
.promotions-section {
  text-align: center;
  margin-bottom: 3rem;
}

.promotions-section h2 {
  font-size: 1.8rem;
  margin-bottom: 1rem;
}

.promotions {
  display: flex;
  gap: 2rem;
  justify-content: center;
  flex-wrap: wrap;
}

.promotion {
  background-color: #f8f8f8;
  border-radius: 8px;
  padding: 1rem;
  width: 250px;
  text-align: left;
}

.promotion h3 {
  font-size: 1.2rem;
  margin-bottom: 0.5rem;
}

.promotion p {
  color: #666;
}

/* Equipo de Trabajo */
.team-section {
  text-align: center;
  margin-bottom: 3rem;
}

.team-section h2 {
  font-size: 1.8rem;
  margin-bottom: 1rem;
}

.team {
  display: flex;
  gap: 2rem;
  justify-content: center;
  flex-wrap: wrap;
}

.team-member {
  background-color: #f8f8f8;
  border-radius: 8px;
  padding: 1rem;
  width: 250px;
  text-align: center;
}

.team-member img {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  margin-bottom: 0.5rem;
}

.team-member h3 {
  font-size: 1.2rem;
  margin-bottom: 0.5rem;
}

.team-member p {
  color: #666;
}

/* Pie de página */
.dashboard-footer {
  background-color: #f8f8f8;
  text-align: center;
  padding: 1rem;
  font-size: 0.9rem;
  color: #555;
  border-top: 1px solid #ddd;
}

.dashboard-footer p {
  margin: 0.5rem 0;
}

.dashboard-footer p:last-child {
  font-size: 0.8rem;
  color: #777;
}
</style>