<template>
    <div class="contact-container">
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
            <router-link to="/">Inicio</router-link> > <span>Contacto</span>
        </div>

        <!-- Contenido principal -->
        <main class="contact-main">
            <div class="contact-content">
                <!-- Sección izquierda - Información de contacto -->
                <div class="contact-info">
                    <h1>Contacta con nosotros</h1>

                    <p class="contact-description">
                        Nuestro objetivo: "Ofrecer lo mejor colección de vehículos para satisfacer a todos nuestros
                        clientes.
                    </p>

                    <!-- Direcciones -->
                    <div class="locations">
                        <p>Avenida Carlos V, s/número 107, 35240, Carrizal, Ingenio</p>
                    </div>

                    <!-- Email -->
                    <div class="contact-email">
                        <img src="/images/email.jpg" alt="Email" class="icon-email" />
                        <p>Atención al cliente: <strong>atencioncliente@vcars.es</strong></p>
                    </div>

                    <!-- Teléfono -->
                    <div class="contact-phone">
                        <div class="phone-number">
                            <span class="country-code">+34</span>
                            <span class="number">928 289 090</span>
                        </div>
                        <p class="website">info@vcars.es</p>
                    </div>



                    <!-- Preguntas frecuentes -->
                    <div class="faq-section">
                        <h2>Preguntas frecuentes</h2>

                        <div class="faq-item">
                            <div class="faq-question" @click="toggleFaq('guarantee')">
                                <p>¿Cuánto dura la garantía?</p>
                                <span class="arrow" :class="{ 'open': openFaqs.guarantee }">▼</span>
                            </div>
                            <div class="faq-answer" v-if="openFaqs.guarantee">
                                <p>Los coches que llegan nuevos tienen un año de garantía, es decir 12 meses desde la
                                    fecha de compra.</p>
                                <p>En el caso de los coches de segunda mano de la garantía es obligatoria y tiene una
                                    duración de un año. Esta garantía actúa en vehículos de ocasión.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" @click="toggleFaq('finance')">
                                <p>¿Se pueden financiar?</p>
                                <span class="arrow" :class="{ 'open': openFaqs.finance }">▼</span>
                            </div>
                            <div class="faq-answer" v-if="openFaqs.finance">
                                <p>Sí, ofrecemos varias opciones de financiación adaptadas a tus necesidades.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" @click="toggleFaq('payment')">
                                <p>¿Puedo elegir el coche como parte del pago?</p>
                                <span class="arrow" :class="{ 'open': openFaqs.payment }">▼</span>
                            </div>
                            <div class="faq-answer" v-if="openFaqs.payment">
                                <p>Sí, puedes entregar tu vehículo actual como parte del pago por tu nuevo coche.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" @click="toggleFaq('delivery')">
                                <p>¿Están incluidos los costes?</p>
                                <span class="arrow" :class="{ 'open': openFaqs.delivery }">▼</span>
                            </div>
                            <div class="faq-answer" v-if="openFaqs.delivery">
                                <p>Todos nuestros precios incluyen los costes asociados con la entrega del vehículo.</p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Sección derecha - Formulario de contacto -->
                <div class="contact-form">
                    <div class="form-container">
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

                        <div class="form-check">
                            <input type="checkbox" v-model="formData.privacidad" id="privacy-check" />
                            <label for="privacy-check">Acepto la <a href="/politica-privacidad"
                                    class="link-yellow">política de privacidad</a></label>
                        </div>

                        <button class="btn-send" @click="enviarFormulario">Enviar</button>
                    </div>
                </div>
            </div>

            <!-- Sección de mapas -->
            <div class="maps-section">
                <div class="map-container">
                    <iframe
                        src="https://maps.google.com/maps?q=Avenida+Carlos+V,+s/número+107,+35240,+Carrizal,+Ingenio&output=embed"
                        width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen=""
                        aria-hidden="false" tabindex="0"></iframe>
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
                            <h5>Carrizal:</h5>
                            <p>Av. Carlos V 107, Ingenio</p>
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
    name: "ContactoPage",
    components: { Link },
    data() {
        return {
            formData: {
                nombre: "",
                email: "",
                telefono: "",
                mensaje: "",
                privacidad: false
            },
            openFaqs: {
                guarantee: false,
                finance: false,
                payment: false,
                delivery: false
            },
            user: null,
            userLoading: true
        };
    },
    mounted() {
        this.fetchUser();
    },
    methods: {
        toggleFaq(faq) {
            this.openFaqs[faq] = !this.openFaqs[faq];
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
                text: 'Formulario enviado con éxito. Nos pondremos en contacto con usted en breve.'
            });
            this.resetFormulario();
        },
        resetFormulario() {
            this.formData = {
                nombre: "",
                email: "",
                telefono: "",
                mensaje: "",
                privacidad: false
            };
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

/* Estilos generales */
.contact-container {
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
.contact-main {
    flex: 1;
    padding: 2rem;
    background-color: #f5f7fa;
}

.contact-content {
    display: flex;
    max-width: 1200px;
    margin: 0 auto 2rem;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.contact-info {
    flex: 1;
    padding: 2rem;
}

.contact-form {
    flex: 1;
    padding: 2rem;
    background-color: #f8f9fa;
}

/* Estilos de la sección de información de contacto */
h1 {
    font-size: 2rem;
    margin-bottom: 1.5rem;
    color: #333;
}

.contact-description {
    margin-bottom: 2rem;
    line-height: 1.6;
}

.locations {
    margin-bottom: 2rem;
}

.locations p {
    margin-bottom: 0.5rem;
    color: #666;
}

.contact-email {
    display: flex;
    align-items: center;
    margin-bottom: 2rem;
}

.icon-email {
    width: 35px;
    height: 20px;
    margin-right: 0rem;
}

.contact-phone {
    margin-bottom: 2rem;
}

.phone-number {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}

.country-code {
    font-size: 1.2rem;
    color: #666;
    margin-right: 0.5rem;
}

.number {
    font-size: 1.5rem;
    font-weight: bold;
    color: #ffcc00;
}

.website {
    color: #666;
    font-size: 0.9rem;
}

.whatsapp-container {
    margin-bottom: 2rem;
}

.whatsapp-image {
    max-width: 50px;
}

/* Estilos de las preguntas frecuentes */
.faq-section {
    margin-bottom: 2rem;
}

.faq-section h2 {
    margin-bottom: 1.5rem;
    color: #333;
}

.faq-item {
    margin-bottom: 1rem;
    border: 1px solid #eee;
    border-radius: 4px;
    overflow: hidden;
}

.faq-question {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background-color: #f9f9f9;
    cursor: pointer;
}

.faq-question p {
    margin: 0;
    font-weight: bold;
}

.arrow {
    font-size: 0.8rem;
    transition: transform 0.3s;
}

.arrow.open {
    transform: rotate(180deg);
}

.faq-answer {
    padding: 1rem;
    background-color: #fff;
    border-top: 1px solid #eee;
}

.faq-answer p {
    margin: 0 0 0.5rem;
    color: #666;
}

/* Redes sociales */
.social-media {
    margin-top: 2rem;
}

.social-media p {
    margin-bottom: 1rem;
    color: #666;
}

.social-icons {
    display: flex;
}

.social-icon {
    width: 40px;
    height: 40px;
    margin-right: 1rem;
    border-radius: 50%;
    background-color: #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.3s;
}

.social-icon:hover {
    background-color: #ffcc00;
}

.social-icon img {
    width: 20px;
    height: 20px;
}

/* Estilos del formulario */
.form-container {
    background-color: #fff;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
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

.btn-send {
    background-color: #ffcc00;
    color: #333;
    border: none;
    padding: 0.8rem 2rem;
    font-size: 1rem;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
    width: 100%;
}

.btn-send:hover {
    background-color: #e6b800;
}

/* Sección de mapas */
.maps-section {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    gap: 2rem;
    margin-bottom: 2rem;
}

.map-container {
    flex: 1;
    height: 300px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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

.footer-contact .email {
    color: #ccc;
    margin-bottom: 1rem;
}

.footer-contact .locations {
    margin-top: 1rem;
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
    .contact-content {
        flex-direction: column;
    }

    .maps-section {
        flex-direction: column;
    }

    .footer-content {
        flex-direction: column;
    }

    .footer-logo,
    .footer-info,
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