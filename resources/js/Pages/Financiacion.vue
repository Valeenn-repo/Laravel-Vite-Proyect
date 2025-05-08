<template>
    <div class="financing-container">
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
                        <!-- Puedes poner un spinner aquí si lo deseas -->
                    </li>
                    <li v-else-if="!user">
                        <Link href="/login" class="login-btn">Iniciar sesión</Link>
                    </li>
                    <li v-else class="user-info">
                        <i class="fas fa-user"></i> {{ user.name || user.email }}
                    </li>
                </ul>
            </nav>
        </header>

        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <router-link to="/">Inicio</router-link> > <span>Financiación</span>
        </div>

        <!-- Contenido principal -->
        <main class="financing-main">
            <div class="financing-form-container">
                <div class="financing-left">
                    <h1>Financia tu próximo <span class="highlight">vehículo</span></h1>

                    <p class="financing-info">
                        En VCars te lo ponemos fácil. Estamos para ayudarte a conseguir la financiación
                        que se ajuste a tus necesidades en el menor <strong>tiempo posible</strong>.
                        Nos encargamos de gestionar tu crédito para que puedas disfrutar
                        cuanto antes de tu vehículo.
                    </p>

                    <p class="consent-text">
                        Estás en contacto con <strong>financiacion@vcars.es</strong> <em>esta dirección</em>
                    </p>

                    <!-- Formulario de financiación -->
                    <div class="form-group">
                        <input type="text" v-model="formData.nombre" placeholder="Nombre" class="form-control" />
                    </div>
                    <div class="form-group">
                        <input type="email" v-model="formData.email" placeholder="Email" class="form-control" />
                    </div>
                    <div class="form-group">
                        <input type="tel" v-model="formData.telefono" placeholder="Teléfono" class="form-control" />
                    </div>
                    <div class="form-group">
                        <textarea v-model="formData.mensaje" placeholder="Mensaje" class="form-control"
                            rows="5"></textarea>
                    </div>

                    <div class="form-file">
                        <p>Subir documentación (máximo 10 MB)</p>
                        <input type="file" ref="fileInput" @change="onFileChange" />
                    </div>

                    <div class="form-check">
                        <input type="checkbox" v-model="formData.privacidad" id="privacy-check" />
                        <label for="privacy-check">Acepto la <a href="/politica-privacidad" class="link-yellow">política
                                de privacidad</a></label>
                    </div>

                    <div class="data-protection">
                        <p><i class="icon-lock"></i> Información adicional sobre la protección de datos</p>
                    </div>

                    <button class="btn-send" @click="enviarFormulario">Enviar</button>
                </div>

                <div class="financing-right">
                    <img src="/images/logo.png" alt="Financiación de vehículo" class="financing-image" />

                    <div class="documentation-section">
                        <h3>Documentación necesaria</h3>
                        <div class="documentation-alert">
                            <i class="icon-alert"></i> En breve un comercial se podrá en contacto contigo
                        </div>

                        <div class="documentation-list">
                            <p>Si eres una asalariado, necesitaremos los siguientes documentos:</p>
                            <ul>
                                <li>DNI</li>
                                <li>2 últimas nóminas</li>
                                <li>Certificado de titularidad de cuenta bancaria o extracto del banco</li>
                                <li>Vida laboral</li>
                                <li>IRPF / Declaración</li>
                            </ul>
                        </div>

                        <!-- Secciones colapsables -->
                        <div class="collapsible-section">
                            <div class="collapsible-header" @click="toggleSection('autonomo')">
                                <i class="icon-plus" v-if="!sections.autonomo"></i>
                                <i class="icon-minus" v-else></i>
                                Autónomo
                            </div>
                            <div class="collapsible-content" v-if="sections.autonomo">
                                <!-- Contenido para autónomos -->
                                <ul>
                                    <li>DNI</li>
                                    <li>Últimas declaraciones trimestrales</li>
                                    <li>Extractos bancarios últimos 3 meses</li>
                                    <li>Último IRPF</li>
                                </ul>
                            </div>
                        </div>

                        <div class="collapsible-section">
                            <div class="collapsible-header" @click="toggleSection('extranjero')">
                                <i class="icon-plus" v-if="!sections.extranjero"></i>
                                <i class="icon-minus" v-else></i>
                                Trabajador extranjero
                            </div>
                            <div class="collapsible-content" v-if="sections.extranjero">
                                <!-- Contenido para trabajadores extranjeros -->
                                <ul>
                                    <li>NIE/Pasaporte</li>
                                    <li>Permiso de residencia</li>
                                    <li>2 últimas nóminas</li>
                                    <li>Extracto bancario</li>
                                </ul>
                            </div>
                        </div>

                        <div class="collapsible-section">
                            <div class="collapsible-header" @click="toggleSection('empresa')">
                                <i class="icon-plus" v-if="!sections.empresa"></i>
                                <i class="icon-minus" v-else></i>
                                Empresas
                            </div>
                            <div class="collapsible-content" v-if="sections.empresa">
                                <!-- Contenido para empresas -->
                                <ul>
                                    <li>CIF de la empresa</li>
                                    <li>Escrituras</li>
                                    <li>Balance y cuenta de resultados</li>
                                    <li>Impuesto de sociedades</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

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
                    <p class="email">info@vcars.es</p>

                    <div class="locations">
                        <div class="location">
                            <h5>Gran Canaria:</h5>
                            <p>Av. Carlos V, Carrixal</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
import Swal from 'sweetalert2'
import axios from 'axios'
import { Link } from '@inertiajs/inertia-vue3'

export default {
    name: "FinanciacionPage",
    components: { Link },
    data() {
        return {
            formData: {
                nombre: "",
                email: "",
                telefono: "",
                mensaje: "",
                privacidad: false,
                archivo: null
            },
            sections: {
                autonomo: false,
                extranjero: false,
                empresa: false
            },
            user: null,
            userLoading: true
        };
    },
    mounted() {
        this.fetchUser();
    },
    methods: {
        toggleSection(section) {
            this.sections[section] = !this.sections[section];
        },
        onFileChange(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 10 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Archivo demasiado grande',
                        text: 'El archivo excede el tamaño máximo permitido (10MB)'
                    });
                    this.$refs.fileInput.value = "";
                } else {
                    this.formData.archivo = file;
                }
            }
        },
        enviarFormulario() {
            // Validación básica
            if (!this.formData.nombre || !this.formData.email || !this.formData.telefono) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos obligatorios',
                    text: 'Por favor, complete los campos obligatorios'
                });
                return;
            }

            if (!this.formData.privacidad) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Política de privacidad',
                    text: 'Por favor, acepte la política de privacidad'
                });
                return;
            }

            // Aquí iría la lógica para enviar el formulario
            console.log("Enviando formulario:", this.formData);

            // Simulamos éxito para demostración
            Swal.fire({
                icon: 'success',
                title: '¡Enviado!',
                text: 'Formulario enviado con éxito. Un asesor se pondrá en contacto con usted en breve.'
            });
            this.resetFormulario();
        },
        resetFormulario() {
            this.formData = {
                nombre: "",
                email: "",
                telefono: "",
                mensaje: "",
                privacidad: false,
                archivo: null
            };
            if (this.$refs.fileInput) {
                this.$refs.fileInput.value = "";
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
        }
    }
};
</script>

<style scoped>
/* Estilos generales */
.financing-container {
    font-family: Arial, sans-serif;
    color: #333;
    background: #fff;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* Navbar (igual que en dashboard) */
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

/* Contenido principal */
.financing-main {
    flex: 1;
    padding: 2rem;
    background-color: #f5f7fa;
}

.financing-form-container {
    display: flex;
    max-width: 1200px;
    margin: 0 auto;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.financing-left {
    flex: 1;
    padding: 2rem;
}

.financing-right {
    flex: 1;
    padding: 2rem;
    background-color: #f8f9fa;
}

/* Estilos del formulario */
h1 {
    font-size: 2rem;
    margin-bottom: 1.5rem;
    color: #333;
}

.highlight {
    color: #ffcc00;
}

.financing-info {
    margin-bottom: 2rem;
    line-height: 1.6;
}

.consent-text {
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
    color: #666;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-control {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

.form-file {
    margin-bottom: 1.5rem;
}

.form-file p {
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    color: #666;
}

.form-check {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
}

.form-check input {
    margin-right: 0.5rem;
}

.link-yellow {
    color: #ffcc00;
    text-decoration: none;
    font-weight: bold;
}

.data-protection {
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
    color: #666;
}

.icon-lock::before {
    content: "🔒";
    margin-right: 0.5rem;
}

.btn-send {
    background-color: #ffcc00;
    color: #333;
    border: none;
    padding: 0.8rem 2rem;
    font-size: 1rem;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-send:hover {
    background-color: #e6b800;
}

/* Imagen y documentación */
.financing-image {
    width: 100%;
    max-width: 400px;
    margin-bottom: 2rem;
    display: block;
}

.documentation-section {
    background-color: #fff;
    border-radius: 6px;
    padding: 1.5rem;
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
}

.documentation-section h3 {
    margin-bottom: 1rem;
    color: #333;
}

.documentation-alert {
    background-color: #fff9e6;
    border-left: 4px solid #ffcc00;
    padding: 1rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
}

.icon-alert::before {
    content: "⚠️";
    margin-right: 0.5rem;
}

.documentation-list {
    margin-bottom: 1.5rem;
}

.documentation-list ul {
    list-style-type: none;
    padding-left: 1.5rem;
    margin-top: 0.5rem;
}

.documentation-list ul li {
    position: relative;
    padding-left: 1.5rem;
    margin-bottom: 0.5rem;
}

.documentation-list ul li::before {
    content: "•";
    color: #ffcc00;
    position: absolute;
    left: 0;
    font-weight: bold;
}

/* Secciones colapsables */
.collapsible-section {
    margin-bottom: 0.5rem;
    border: 1px solid #eee;
    border-radius: 4px;
    overflow: hidden;
}

.collapsible-header {
    background-color: #f5f5f5;
    padding: 1rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    font-weight: bold;
}

.icon-plus::before {
    content: "+";
    margin-right: 0.5rem;
    font-weight: bold;
}

.icon-minus::before {
    content: "-";
    margin-right: 0.5rem;
    font-weight: bold;
}

.collapsible-content {
    padding: 1rem;
    background-color: #fff;
}

.collapsible-content ul {
    list-style-type: none;
    padding-left: 0;
}

.collapsible-content ul li {
    position: relative;
    padding-left: 1.5rem;
    margin-bottom: 0.5rem;
}

.collapsible-content ul li::before {
    content: "•";
    color: #ffcc00;
    position: absolute;
    left: 0;
    font-weight: bold;
}

/* La mejor opción */
.best-option {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 3rem 0;
    background-color: #f8f8f8;
}

.best-option img {
    height: 80px;
}

.option-text {
    margin-left: 1rem;
    font-style: italic;
    color: #666;
}

.option-text p {
    margin: 0;
    line-height: 1.2;
}

.option-text .website {
    font-size: 0.8rem;
}

/* Footer */
.dashboard-footer {
    background-color: #1a1a1a;
    color: #fff;
    padding: 2rem;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
}

.footer-logo img {
    height: 60px;
}

.footer-info h4,
.footer-contact h5 {
    color: #fff;
    margin-top: 0;
    margin-bottom: 0.5rem;
}

.footer-info p {
    margin: 0.2rem 0;
    color: #ccc;
}

.footer-links ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.footer-links ul li {
    margin-bottom: 0.5rem;
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

.footer-links ul li a {
    color: #ccc;
    text-decoration: none;
    transition: color 0.3s;
}

.footer-links ul li a:hover {
    color: #ffcc00;
}

.footer-contact .phone {
    font-size: 1.2rem;
    font-weight: bold;
    color: #ffcc00;
    margin-bottom: 0.5rem;
}

.footer-contact .email {
    color: #ccc;
    margin-bottom: 1rem;
}

.locations {
    margin-top: 1rem;
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

.location {
    margin-bottom: 0.5rem;
}

.location h5 {
    margin-bottom: 0.2rem;
    font-size: 0.9rem;
}

.location p {
    margin: 0;
    color: #ccc;
    font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 992px) {
    .financing-form-container {
        flex-direction: column;
    }

    .footer-content {
        flex-direction: column;
    }

    .footer-logo,
    .footer-info,
    .footer-links,
    .footer-contact {
        margin-bottom: 2rem;
    }
}

@media (max-width: 768px) {
    .navbar {
        flex-direction: column;
    }

    .navbar .nav-links {
        margin-top: 1rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .navbar .nav-links li {
        margin: 0.5rem 1rem;
    }
}
</style>