<template>
    <div class="buy-car-container">
        <!-- Encabezado / Barra de navegación -->
        <header class="dashboard-header">
            <nav class="navbar">
                <div class="logo">
                    <img src="/images/logo.png" alt="Victory Cars" />
                </div>
                <ul class="nav-links">
                    <li>
                        <Link href="/dashboard">Inicio</Link>
                    </li>
                    <li>
                        <Link href="/compramos-tu-coche">Compramos tu coche</Link>
                    </li>
                    <li>
                        <Link href="/vehiculos">Vehículos</Link>
                    </li>
                    <li>
                        <Link href="/financiacion">Financiación</Link>
                    </li>
                    <li>
                        <Link href="/contacto">Contacto</Link>
                    </li>
                <li v-if="userLoading">
                    <!-- Optionally, show a spinner or nothing while loading -->
                </li>
                <li v-else-if="!user">
                    <Link href="/login" class="login-btn">Iniciar sesión</Link>
                </li>
                <li v-else class="user-info">
                    <i class="fas fa-user"></i> {{ user.name || user.email }}
                </li>
            </ul>
        </nav>
        <!-- Sección Hero -->
            <div class="hero-section">
                <div class="hero-content">
                    <h1>Compramos tu coche</h1>
                    <p>Obtén la mejor tasación para tu vehículo en menos de 24 horas</p>
                    <button @click="scrollToForm">Solicitar Tasación</button>
                </div>
            </div>
        </header>

        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <router-link to="/">Inicio</router-link> > <span>Compramos tu coche</span>
        </div>

        <!-- Contenido principal -->
        <main class="buy-car-main">
            <div class="content-wrapper">
                <!-- Columna izquierda: Información -->
                <div class="info-column">
                    <div class="info-card">
                        <h2>Vende tu Coche con Nosotros</h2>
                        <p class="highlight-text">La forma más fácil y segura de vender tu vehículo</p>

                        <div class="benefits">
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="icon-money"></i>
                                </div>
                                <div class="benefit-text">
                                    <h3>Mejor Precio</h3>
                                    <p>Valoramos tu vehículo al mejor precio del mercado</p>
                                </div>
                            </div>

                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="icon-clock"></i>
                                </div>
                                <div class="benefit-text">
                                    <h3>Proceso Rápido</h3>
                                    <p>Tasación y pago en menos de 24 horas</p>
                                </div>
                            </div>

                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="icon-document"></i>
                                </div>
                                <div class="benefit-text">
                                    <h3>Sin Papeleo</h3>
                                    <p>Nos encargamos de todos los trámites</p>
                                </div>
                            </div>
                        </div>

                        <div class="process-steps">
                            <h3>¿Cómo funciona?</h3>
                            <div class="step">
                                <div class="step-number">1</div>
                                <div class="step-text">
                                    <h4>Rellena el formulario</h4>
                                    <p>Dinos los datos básicos de tu vehículo</p>
                                </div>
                            </div>

                            <div class="step">
                                <div class="step-number">2</div>
                                <div class="step-text">
                                    <h4>Envía fotos</h4>
                                    <p>Proporciónanos fotos detalladas de tu coche</p>
                                </div>
                            </div>

                            <div class="step">
                                <div class="step-number">3</div>
                                <div class="step-text">
                                    <h4>Recibe tu oferta</h4>
                                    <p>Te enviamos nuestra mejor oferta sin compromiso</p>
                                </div>
                            </div>

                            <div class="step">
                                <div class="step-number">4</div>
                                <div class="step-text">
                                    <h4>Firma y cobra</h4>
                                    <p>Si aceptas, firmamos y pagas inmediatamente</p>
                                </div>
                            </div>
                        </div>

                        <div class="trade-in-info">
                            <h3>¿Quieres cambiar tu coche?</h3>
                            <p>También puedes entregar tu coche como parte del pago para tu próximo vehículo. Consulta
                                nuestras condiciones especiales de financiación para clientes que realizan trade-in.</p>
                            <router-link to="/vehiculos" class="browse-link">Ver vehículos disponibles</router-link>
                        </div>
                    </div>

                    <div class="photo-requirements">
                        <h3>Documentación necesaria</h3>
                        <p>Para una tasación más rápida y precisa, necesitaremos que nos envíes las siguientes
                            fotografías y documentos:</p>

                        <div class="requirements-grid">
                            <div class="requirement-item">
                                <div class="requirement-icon">📷</div>
                                <p>Foto Frontal</p>
                            </div>
                            <div class="requirement-item">
                                <div class="requirement-icon">📷</div>
                                <p>Foto Trasera</p>
                            </div>
                            <div class="requirement-item">
                                <div class="requirement-icon">📷</div>
                                <p>Lateral derecho</p>
                            </div>
                            <div class="requirement-item">
                                <div class="requirement-icon">📷</div>
                                <p>Lateral izquierdo</p>
                            </div>
                            <div class="requirement-item">
                                <div class="requirement-icon">📷</div>
                                <p>Interior delantero</p>
                            </div>
                            <div class="requirement-item">
                                <div class="requirement-icon">📷</div>
                                <p>Interior trasero</p>
                            </div>
                            <div class="requirement-item">
                                <div class="requirement-icon">📷</div>
                                <p>Cuadro de mandos</p>
                            </div>
                            <div class="requirement-item">
                                <div class="requirement-icon">📷</div>
                                <p>Motor</p>
                            </div>
                            <div class="requirement-item">
                                <div class="requirement-icon">📷</div>
                                <p>Maletero</p>
                            </div>
                            <div class="requirement-item">
                                <div class="requirement-icon">📷</div>
                                <p>Rueda repuesto</p>
                            </div>
                            <div class="requirement-item">
                                <div class="requirement-icon">📑</div>
                                <p>Ficha técnica</p>
                            </div>
                            <div class="requirement-item">
                                <div class="requirement-icon">📑</div>
                                <p>Permiso de circulación</p>
                            </div>
                        </div>

                        <div class="whatsapp-info">
                            <p>Envía las fotos a nuestro WhatsApp para una respuesta más rápida</p>
                            <a href="https://wa.me/34928289090" class="whatsapp-button">
                                <i class="icon-whatsapp"></i> Enviar por WhatsApp
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha: Formulario -->
                <div class="form-column" id="formulario-tasacion">
                    <div class="form-card">
                        <h2>Solicitar Tasación</h2>
                        <p>Completa el formulario y recibe una valoración en menos de 24h</p>

                        <form @submit.prevent="enviarFormulario">
                            <div class="form-section">
                                <h3>Datos del vehículo</h3>

                                <div class="form-group">
                                    <label for="marca">Marca*</label>
                                    <select id="marca" v-model="formData.marca" required>
                                        <option value="" disabled selected>Selecciona la marca</option>
                                        <option v-for="marca in marcas" :key="marca" :value="marca">{{ marca }}</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="modelo">Modelo*</label>
                                    <input type="text" id="modelo" v-model="formData.modelo" placeholder="Modelo"
                                        required>
                                </div>

                                <div class="form-row">
                                    <div class="form-group half">
                                        <label for="ano">Año*</label>
                                        <input type="number" id="ano" v-model="formData.ano" placeholder="Año"
                                            min="1900" :max="currentYear" required>
                                    </div>

                                    <div class="form-group half">
                                        <label for="km">Kilómetros*</label>
                                        <input type="number" id="km" v-model="formData.km" placeholder="Kilómetros"
                                            min="0" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group half">
                                        <label for="combustible">Combustible*</label>
                                        <select id="combustible" v-model="formData.combustible" required>
                                            <option value="" disabled selected>Selecciona</option>
                                            <option value="Gasolina">Gasolina</option>
                                            <option value="Diesel">Diesel</option>
                                            <option value="Híbrido">Híbrido</option>
                                            <option value="Eléctrico">Eléctrico</option>
                                            <option value="GLP">GLP</option>
                                        </select>
                                    </div>

                                    <div class="form-group half">
                                        <label for="cv">Potencia (CV)</label>
                                        <input type="number" id="cv" v-model="formData.cv" placeholder="CV" min="0">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="version">Versión/Acabado</label>
                                    <input type="text" id="version" v-model="formData.version"
                                        placeholder="Versión o acabado">
                                </div>
                            </div>

                            <div class="form-section">
                                <h3>Estado del vehículo</h3>

                                <div class="form-group">
                                    <label>Estado general*</label>
                                    <div class="radio-group">
                                        <label class="radio-label">
                                            <input type="radio" v-model="formData.estado" value="Excelente" required>
                                            Excelente
                                        </label>
                                        <label class="radio-label">
                                            <input type="radio" v-model="formData.estado" value="Bueno" required>
                                            Bueno
                                        </label>
                                        <label class="radio-label">
                                            <input type="radio" v-model="formData.estado" value="Regular" required>
                                            Regular
                                        </label>
                                        <label class="radio-label">
                                            <input type="radio" v-model="formData.estado" value="Necesita reparaciones"
                                                required>
                                            Necesita reparaciones
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="descripcion">Descripción adicional</label>
                                    <textarea id="descripcion" v-model="formData.descripcion"
                                        placeholder="Cuéntanos más sobre tu vehículo (mantenimiento, extras, daños, etc.)"
                                        rows="4"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="fotos">Fotos del vehículo</label>
                                    <input type="file" id="fotos" @change="handleFileUpload" multiple accept="image/*">
                                    <p class="form-help">Puedes subir hasta 5 fotos (máx. 5MB cada una)</p>
                                </div>
                            </div>

                            <div class="form-section">
                                <h3>Datos de contacto</h3>

                                <div class="form-row">
                                    <div class="form-group half">
                                        <label for="nombre">Nombre*</label>
                                        <input type="text" id="nombre" v-model="formData.nombre" placeholder="Tu nombre"
                                            required>
                                    </div>

                                    <div class="form-group half">
                                        <label for="apellidos">Apellidos*</label>
                                        <input type="text" id="apellidos" v-model="formData.apellidos"
                                            placeholder="Tus apellidos" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group half">
                                        <label for="telefono">Teléfono*</label>
                                        <input type="tel" id="telefono" v-model="formData.telefono"
                                            placeholder="Teléfono de contacto" required>
                                    </div>

                                    <div class="form-group half">
                                        <label for="email">Email*</label>
                                        <input type="email" id="email" v-model="formData.email" placeholder="Tu email"
                                            required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="horario">Horario preferido de contacto</label>
                                    <select id="horario" v-model="formData.horario">
                                        <option value="" disabled selected>Selecciona un horario</option>
                                        <option value="Mañana (9:00-14:00)">Mañana (9:00-14:00)</option>
                                        <option value="Tarde (16:30-20:00)">Tarde (16:30-20:00)</option>
                                        <option value="Cualquier hora">Cualquier hora</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" id="privacidad" v-model="formData.privacidad" required>
                                <label for="privacidad">Acepto la <a href="/politica-privacidad"
                                        class="link-yellow">política de privacidad</a> y el tratamiento de mis
                                    datos*</label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" id="comunicaciones" v-model="formData.comunicaciones">
                                <label for="comunicaciones">Deseo recibir comunicaciones comerciales de VCars</label>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn-primary">Solicitar Tasación</button>
                                <p class="form-note">* Campos obligatorios</p>
                            </div>
                        </form>
                    </div>

                    <div class="testimonials">
                        <h3>Lo que dicen nuestros clientes</h3>

                        <div class="testimonial">
                            <div class="testimonial-rating">★★★★★</div>
                            <p class="testimonial-text">"Proceso rápido y sencillo. Me dieron un buen precio por mi
                                coche y en menos de 24h ya tenía el dinero en mi cuenta."</p>
                            <p class="testimonial-author">- Carlos M.</p>
                        </div>

                        <div class="testimonial">
                            <div class="testimonial-rating">★★★★★</div>
                            <p class="testimonial-text">"Muy profesionales. Se encargaron de todo el papeleo y me
                                ofrecieron un precio justo. Totalmente recomendable."</p>
                            <p class="testimonial-author">- Laura T.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Sección de CTA -->
        <div class="cta-section">
            <div class="cta-content">
                <h2>¿Tienes dudas sobre la venta de tu coche?</h2>
                <p>Llámanos y te asesoramos sin compromiso</p>
                <a href="tel:+34928289090" class="cta-button">
                    <i class="icon-phone"></i> 928 289 090
                </a>
            </div>
        </div>

        <!-- Pie de página con logo -->
        <div class="best-option">
            <img src="/images/logo.png" alt="La mejor opción" />
            <div class="option-text">
                <p>la mejor</p>
                <p>opción</p>
                <p class="website">vcars.es</p>
            </div>
        </div>

        <!-- Footer -->
        <footer class="dashboard-footer">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="/images/logo.png" alt="Victory Cars" />
                </div>

                <div class="footer-info">
                    <h4>Horario</h4>
                    <p>Lunes a Viernes:</p>
                    <p>9:00-14:00 y 16:30-20:00</p>
                    <p>Sábado:</p>
                    <p>10:00-13:00</p>
                </div>

                <div class="footer-contact">
                    <p class="phone">+34 928 289 090</p>
                    <p class="email">info@vcars.es</p>

                    <div class="locations">
                        <div class="location">
                            <h5>Gran Canaria:</h5>
                            <p>Av. Carlos V, Carrizal</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
  
  <script>
import Swal from 'sweetalert2'
import axios from 'axios';
import { Link } from '@inertiajs/inertia-vue3';

export default {
    name: "CompramosTuCochePage",
    components: { Link },
    data() {
        return {
            user: null,
            userLoading: true,
            userLoading: true,
            formData: {
                marca: "",
                modelo: "",
                ano: "",
                km: "",
                combustible: "",
                cv: "",
                version: "",
                estado: "",
                descripcion: "",
                fotos: [],
                nombre: "",
                apellidos: "",
                telefono: "",
                email: "",
                horario: "",
                privacidad: false,
                comunicaciones: false
            },
            marcas: [
                "Audi", "BMW", "Citroën", "Fiat", "Ford", "Honda", "Hyundai", "Kia", "Mazda", "Mercedes-Benz", "Nissan", "Opel", "Peugeot", "Renault", "Seat", "Skoda", "Toyota", "Volkswagen", "Volvo"
            ],
            currentYear: new Date().getFullYear()
        };
    },
    mounted() {
        this.fetchUser();
    },
    methods: {
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
        scrollToForm() {
            const formSection = document.getElementById('formulario-tasacion');
            if (formSection) {
                formSection.scrollIntoView({ behavior: 'smooth' });
            }
        },
        handleFileUpload(event) {
            const files = event.target.files;
            if (files.length > 5) {
                Swal.fire({
                    icon: 'error',
                    title: 'Demasiadas fotos',
                    text: 'Puedes subir hasta 5 fotos como máximo.'
                });
                event.target.value = "";
                return;
            }
            for (let i = 0; i < files.length; i++) {
                if (files[i].size > 5 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Archivo demasiado grande',
                        text: 'Cada foto debe pesar menos de 5MB.'
                    });
                    event.target.value = "";
                    return;
                }
            }
            this.formData.fotos = Array.from(files);
        },
        enviarFormulario() {
            // Validación básica
            if (!this.formData.marca || !this.formData.modelo || !this.formData.ano || !this.formData.km ||
                !this.formData.combustible || !this.formData.estado || !this.formData.nombre ||
                !this.formData.apellidos || !this.formData.telefono || !this.formData.email || !this.formData.privacidad) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos obligatorios',
                    text: 'Por favor, complete todos los campos obligatorios marcados con *'
                });
                return;
            }

            // Aquí iría la lógica para enviar el formulario
            console.log("Enviando formulario:", this.formData);

            Swal.fire({
                icon: 'success',
                title: '¡Solicitud enviada!',
                text: 'Hemos recibido tu solicitud de tasación. Nos pondremos en contacto contigo en menos de 24h.'
            });

            this.resetFormulario();
        },
        resetFormulario() {
            this.formData = {
                marca: "",
                modelo: "",
                ano: "",
                km: "",
                combustible: "",
                cv: "",
                version: "",
                estado: "",
                descripcion: "",
                fotos: [],
                nombre: "",
                apellidos: "",
                telefono: "",
                email: "",
                horario: "",
                privacidad: false,
                comunicaciones: false
            };
            const fileInput = document.getElementById('fotos');
            if (fileInput) fileInput.value = "";
        }
    }
};
</script>
  
  <style scoped>
/* Estilos generales */
.buy-car-container {
    font-family: Arial, sans-serif;
    color: #333;
    background: #f5f7fa;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* Navbar (igual que en dashboard) */
.login-btn {
  background: #ffcc00;
  color: #232526 !important;
  padding: 8px 18px;
  border-radius: 5px;
  font-weight: 600;
  margin-left: 1.5rem;
  transition: background 0.2s;
}
.login-btn:hover {
  background: #ffbb00;
  color: #232526 !important;
}
.user-info {
  color: #ffcc00;
  font-weight: 600;
  margin-left: 1.5rem;
  display: flex;
  align-items: center;
  gap: 6px;
}
.user-info i {
  font-size: 1.1rem;
}
.dashboard-header {
    position: relative;
    background-color: #000;
    width: 100%;
    margin: 0;
    padding: 0;
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

.navbar .nav-links li a {
    color: #fff;
    text-decoration: none;
}

.navbar .nav-links li a:hover,
.navbar .nav-links li a.active {
    color: #ffcc00;
}

/* Breadcrumb */
.breadcrumb {
    padding: 1rem 2rem;
    background-color: #f5f7fa;
    font-size: 0.9rem;
}

.breadcrumb a {
    color: #666;
    text-decoration: none;
}

.breadcrumb span {
    color: #ffcc00;
    font-weight: bold;
}

/* Hero Section */
.hero-section {
    position: relative;
    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
        url('/images/sell-car-hero.jpg') center center / cover no-repeat;
    color: #fff;
    padding: 6rem 2rem;
    text-align: center;
}

.hero-content {
    max-width: 800px;
    margin: 0 auto;
}

.hero-section h1 {
    font-size: 3rem;
    margin-bottom: 1.5rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
}

.hero-section p {
    font-size: 1.5rem;
    margin-bottom: 2rem;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
}

.hero-section button {
    padding: 1rem 2rem;
    font-size: 1.1rem;
    background-color: #ffcc00;
    color: #333;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s;
    font-weight: bold;
}

.hero-section button:hover {
    background-color: #fff;
    transform: translateY(-3px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
}

/* Main Content */
.buy-car-main {
    padding: 3rem 2rem;
}

.content-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    gap: 2rem;
}

.info-column {
    flex: 1;
}

.form-column {
    flex: 1;
}

/* Info Card */
.info-card {
    background-color: #fff;
    border-radius: 8px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.info-card h2 {
    font-size: 1.8rem;
    margin-bottom: 1rem;
    color: #333;
}

.highlight-text {
    font-size: 1.2rem;
    color: #666;
    margin-bottom: 2rem;
}

/* Benefits */
.benefits {
    margin-bottom: 2rem;
}

.benefit-item {
    display: flex;
    margin-bottom: 1.5rem;
}

.benefit-icon {
    width: 50px;
    height: 50px;
    background-color: #ffcc00;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
}

.icon-money::before {
    content: "💰";
    font-size: 1.5rem;
}

.icon-clock::before {
    content: "⏱️";
    font-size: 1.5rem;
}

.icon-document::before {
    content: "📄";
    font-size: 1.5rem;
}

.benefit-text h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.2rem;
}

.benefit-text p {
    margin: 0;
    color: #666;
}

/* Process Steps */
.process-steps {
    margin-bottom: 2rem;
}

.process-steps h3 {
    margin-bottom: 1.5rem;
    font-size: 1.4rem;
}

.step {
    display: flex;
    margin-bottom: 1.5rem;
}

.step-number {
    width: 40px;
    height: 40px;
    background-color: #333;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 1rem;
    flex-shrink: 0;
}

.step-text h4 {
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
}

.step-text p {
    margin: 0;
    color: #666;
}

/* Trade-in Info */
.trade-in-info {
    background-color: #f9f9f9;
    padding: 1.5rem;
    border-radius: 6px;
    border-left: 4px solid #ffcc00;
}

.trade-in-info h3 {
    margin-top: 0;
    margin-bottom: 1rem;
    font-size: 1.3rem;
}

.browse-link {
    display: inline-block;
    margin-top: 1rem;
    color: #ffcc00;
    font-weight: bold;
    text-decoration: none;
}

.browse-link:hover {
    text-decoration: underline;
}

/* Photo Requirements */
.photo-requirements {
    background-color: #fff;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.photo-requirements h3 {
    margin-top: 0;
    margin-bottom: 1rem;
    font-size: 1.4rem;
}

.requirements-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin: 2rem 0;
}

.requirement-item {
    text-align: center;
}

.requirement-icon {
    margin-bottom: 0.5rem;
    font-size: 1.5rem;
}

.requirement-item p {
    margin: 0;
    font-size: 0.9rem;
}

.whatsapp-info {
    text-align: center;
    margin-top: 2rem;
}

.whatsapp-button {
    display: inline-flex;
    align-items: center;
    background-color: #25d366;
    color: #fff;
    padding: 0.8rem 1.5rem;
    border-radius: 4px;
    text-decoration: none;
    font-weight: bold;
    margin-top: 1rem;
    transition: background-color 0.3s;
}

.icon-whatsapp::before {
    content: "📱";
    margin-right: 0.5rem;
}

.whatsapp-button:hover {
    background-color: #128c7e;
}

/* Form Card */
.form-card {
    background-color: #fff;
    border-radius: 8px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.form-card h2 {
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
    color: #333;
}

.form-card p {
    color: #666;
    margin-bottom: 2rem;
}

/* Form Styles */
.form-section {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #eee;
}

.form-section h3 {
    font-size: 1.3rem;
    margin-bottom: 1.5rem;
    color: #333;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-row {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-group.half {
    width: 50%;
}

label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: bold;
    color: #555;
}

input[type="text"],
input[type="email"],
input[type="tel"],
input[type="number"],
select,
textarea {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

textarea {
    resize: vertical;
    min-height: 100px;
}

.radio-group {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.radio-label {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.radio-label input {
    margin-right: 0.5rem;
}

.form-help {
    font-size: 0.85rem;
    color: #777;
    margin-top: 0.3rem;
}

.form-check {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.form-check input {
    margin-right: 0.5rem;
    margin-top: 0.3rem;
}

.link-yellow {
    color: #ffcc00;
    text-decoration: none;
}

.link-yellow:hover {
    text-decoration: underline;
}

.form-actions {
    margin-top: 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.btn-primary {
    padding: 1rem 2rem;
    font-size: 1.1rem;
    background-color: #ffcc00;
    color: #333;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s;
    font-weight: bold;
    width: 100%;
    max-width: 300px;
}

.btn-primary:hover {
    background-color: #e6b800;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.form-note {
    margin-top: 1rem;
    font-size: 0.9rem;
    color: #777;
}

/* Testimonials */
.testimonials {
    background-color: #fff;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.testimonials h3 {
    margin-top: 0;
    margin-bottom: 1.5rem;
    font-size: 1.4rem;
    text-align: center;
}

.testimonial {
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    background-color: #f9f9f9;
    border-radius: 6px;
    border-left: 4px solid #ffcc00;
}

.testimonial:last-child {
    margin-bottom: 0;
}

.testimonial-rating {
    color: #ffcc00;
    margin-bottom: 1rem;
    font-size: 1.2rem;
}

.testimonial-text {
    font-style: italic;
    margin-bottom: 0.5rem;
}

.testimonial-author {
    text-align: right;
    font-weight: bold;
}

/* CTA Section */
.cta-section {
    background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)),
        url('/images/cta-background.jpg') center center / cover no-repeat;
    padding: 4rem 2rem;
    text-align: center;
    color: #fff;
}

.cta-content {
    max-width: 800px;
    margin: 0 auto;
}

.cta-section h2 {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.cta-section p {
    font-size: 1.2rem;
    margin-bottom: 2rem;
}

.cta-button {
    display: inline-flex;
    align-items: center;
    background-color: #ffcc00;
    color: #333;
    padding: 1rem 2rem;
    border-radius: 4px;
    text-decoration: none;
    font-weight: bold;
    font-size: 1.2rem;
    transition: all 0.3s;
}

.icon-phone::before {
    content: "📞";
    margin-right: 0.5rem;
}

.cta-button:hover {
    background-color: #fff;
    transform: translateY(-3px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
}

/* Best Option */
.best-option {
    background-color: #000;
    color: #fff;
    padding: 2rem;
    display: flex;
    justify-content: center;
    align-items: center;
}

.best-option img {
    height: 80px;
    margin-right: 1rem;
}

.option-text {
    text-align: center;
}

.option-text p {
    margin: 0;
    font-size: 1.5rem;
    font-weight: bold;
    text-transform: uppercase;
    line-height: 1.2;
}

.website {
    color: #ffcc00;
    font-size: 1rem;
    margin-top: 0.5rem;
}

/* Footer */
.dashboard-footer {
    background-color: #222;
    color: #fff;
    padding: 3rem 2rem;
}

.footer-content {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 2rem;
}

.footer-logo img {
    height: 60px;
}

.footer-info h4,
.footer-links h4,
.footer-contact h4 {
    margin-top: 0;
    margin-bottom: 1rem;
    color: #ffcc00;
    font-size: 1.2rem;
}

.footer-info p {
    margin: 0.3rem 0;
    font-size: 0.9rem;
}

.footer-links ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 0.8rem;
}

.footer-links a {
    color: #fff;
    text-decoration: none;
}

.footer-links a:hover {
    color: #ffcc00;
}

.footer-contact .phone,
.footer-contact .email {
    margin-bottom: 1rem;
}

.locations {
    margin-top: 1.5rem;
}

.location {
    margin-bottom: 1rem;
}

.location h5 {
    margin: 0 0 0.3rem 0;
    color: #ffcc00;
    font-size: 1rem;
}

.location p {
    margin: 0;
    font-size: 0.9rem;
}

/* Responsive adjustments */
@media (max-width: 1024px) {
    .content-wrapper {
        flex-direction: column;
    }

    .info-column,
    .form-column {
        width: 100%;
    }
}

@media (max-width: 768px) {
    .navbar {
        flex-direction: column;
        padding: 1rem;
    }

    .navbar .logo {
        margin-bottom: 1rem;
    }

    .navbar .nav-links {
        flex-wrap: wrap;
        justify-content: center;
    }

    .navbar .nav-links li {
        margin: 0.5rem 1rem;
    }

    .hero-section h1 {
        font-size: 2.5rem;
    }

    .hero-section p {
        font-size: 1.2rem;
    }

    .form-row {
        flex-direction: column;
        gap: 0;
    }

    .form-group.half {
        width: 100%;
    }

    .requirements-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .footer-content {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .hero-section {
        padding: 4rem 1rem;
    }

    .buy-car-main {
        padding: 2rem 1rem;
    }

    .requirements-grid {
        grid-template-columns: 1fr;
    }

    .radio-group {
        flex-direction: column;
        gap: 0.5rem;
    }

    .cta-section h2 {
        font-size: 1.5rem;
    }

    .cta-section p {
        font-size: 1rem;
    }
}
</style>