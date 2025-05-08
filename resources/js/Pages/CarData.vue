<template>
    <div class="car-detail-container">
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
                        <Link href="/vehiculos" class="active">Vehículos</Link>
                    </li>
                    <li>
                        <Link href="/financiacion">Financiación</Link>
                    </li>
                    <li>
                        <Link href="/contacto">Contacto</Link>
                    </li>
                    <li v-if="userLoading">
                        <!-- Spinner para carga -->
                    </li>
                    <li v-else-if="!user">
                        <Link href="/login" class="login-btn">Iniciar sesión</Link>
                    </li>
                    <li v-else class="user-info" @click="showUserMenu = !showUserMenu" style="position: relative;">
                        <div style="cursor:pointer;">
                            <i class="fas fa-user"></i> {{ user.name || user.email }}
                            <i class="fas fa-chevron-down" style="margin-left:6px;font-size:0.9em;"></i>
                        </div>
                        <transition name="fade">
                            <div v-if="showUserMenu" class="user-dropdown">
                                <div class="dropdown-arrow"></div>
                                <button @click.stop="logout" class="logout-btn">
                                    <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                                </button>
                            </div>
                        </transition>
                    </li>
                </ul>
            </nav>
        </header>

        <!-- Contenido principal -->
        <main class="car-detail-main">
            <!-- Ruta de navegación -->
            <div class="breadcrumb">
                <Link href="/">Inicio</Link> &gt;
                <Link href="/vehiculos">Vehículos</Link> &gt;
                <span>{{ coche.marca }} {{ coche.modelo }}</span>
            </div>

            <!-- Sección principal con detalles del coche -->
            <div class="detail-content">
                <!-- Imagen única del coche -->
                <div class="car-gallery">
                    <div class="main-image" style="position: relative;">
                        <img :src="coche.imagen || '/images/car-placeholder.jpg'"
                            :alt="coche.marca + ' ' + coche.modelo" />
                        <span v-if="coche.status === 'sold'"
                            style="position:absolute;top:10px;left:10px;background:#dc3545;color:#fff;padding:8px 16px;border-radius:6px;font-weight:bold;font-size:1.2em;box-shadow:0 2px 8px rgba(0,0,0,0.15);z-index:2;">
                            VENDIDO
                        </span>
                        <span v-else-if="reservado"
                            style="position:absolute;top:10px;left:10px;background:#e74c3c;color:#fff;padding:8px 16px;border-radius:6px;font-weight:bold;font-size:1.2em;box-shadow:0 2px 8px rgba(0,0,0,0.15);z-index:2;">
                            RESERVADO
                        </span>
                    </div>
                </div>

                <!-- Información del coche -->
                <div class="car-info">
                    <div class="car-header">
                        <h1>{{ coche.marca }} {{ coche.modelo }} {{ coche.descripcion }}</h1>
                        <p class="car-tagline">{{ coche.combustible }} · {{ coche.year }} · {{
                            formatNumber(coche.kilometros) }} km</p>
                    </div>

                    <div class="price-section">
                        <div class="price-box">
                            <div class="current-price">{{ formatPrice(coche.precio) }} €</div>
                            <button class="share-btn">
                                <i class="fas fa-share-alt"></i>
                            </button>
                        </div>
                        <div class="financing">
                            <span class="finance-label">Financiación:</span>
                            <span class="finance-price">{{ coche.cuota_mensual }}€/mes</span>
                        </div>
                    </div>

                    <div class="cta-buttons">
                        <button class="reserve-btn" @click="reservar" :disabled="coche.status === 'sold'">
                            <i class="fas fa-check"></i> Reservar
                        </button>
                        <button class="call-btn">
                            <i class="fas fa-phone"></i> Llamar ahora
                            <span>+34 928 292 094</span>
                        </button>
                        <button class="whatsapp-btn">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </button>
                    </div>

                    <!-- Calculadora financiera -->
                    <div class="finance-calculator">
                        <h3>Calculadora financiera</h3>
                        <div class="calculator-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Periodo (años)</label>
                                    <div class="radio-options">
                                        <label class="radio-option">
                                            <input type="radio" v-model="financePeriod" value="3" />
                                            <span>3</span>
                                        </label>
                                        <label class="radio-option">
                                            <input type="radio" v-model="financePeriod" value="4" />
                                            <span>4</span>
                                        </label>
                                        <label class="radio-option">
                                            <input type="radio" v-model="financePeriod" value="5" checked />
                                            <span>5</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Cuota inicial</label>
                                    <input type="text" v-model="initialPayment" class="price-input" />
                                </div>
                            </div>
                            <div class="calc-result">
                                <strong>Pago mensual:</strong>
                                <span class="monthly-result">{{ calculatedMonthly }}€</span>
                            </div>
                            <p class="finance-disclaimer">
                                * El resultado obtenido es orientativo y puede variar en función de la aprobación
                                financiera.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información técnica del vehículo -->
            <div class="car-specs">
                <h2>Características técnicas</h2>
                <div class="specs-grid">
                    <div class="spec-item">
                        <span class="spec-label">Marca</span>
                        <span class="spec-value">{{ coche.marca }}</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Modelo</span>
                        <span class="spec-value">{{ coche.modelo }}</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Año</span>
                        <span class="spec-value">{{ coche.year }}</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Kilómetros</span>
                        <span class="spec-value">{{ formatNumber(coche.kilometros) }} km</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Combustible</span>
                        <span class="spec-value">{{ coche.combustible }}</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Transmisión</span>
                        <span class="spec-value">{{ coche.transmision }}</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Color</span>
                        <span class="spec-value">{{ coche.color }}</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Plazas</span>
                        <span class="spec-value">{{ coche.plazas || '5' }}</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Puertas</span>
                        <span class="spec-value">{{ coche.puertas || '5' }}</span>
                    </div>
                </div>
            </div>

            <!-- Descripción del vehículo -->
            <div class="car-description">
                <h2>Descripción</h2>
                <div class="description-content">
                    <p>{{ coche.descripcion_completa || generarDescripcion() }}</p>
                </div>
                <button class="show-more-btn" @click="showFullDescription = !showFullDescription">
                    {{ showFullDescription ? 'Mostrar menos' : 'Leer más' }}
                </button>
            </div>

            <!-- Equipamiento -->
            <div class="car-equipment">
                <h2>Equipamiento</h2>
                <div class="equipment-list">
                    <div v-for="(item, index) in equipamiento" :key="index" class="equipment-item">
                        <i class="fas fa-check"></i> {{ item }}
                    </div>
                </div>
            </div>

            <!-- Coches relacionados -->
            <div class="related-cars">
                <h2>Vehículos relacionados</h2>
                <div class="related-cars-grid">
                    <div v-for="relatedCar in relatedCars" :key="relatedCar.id" class="related-car-card">
                        <img :src="relatedCar.imagen" :alt="relatedCar.marca + ' ' + relatedCar.modelo" />
                        <div class="related-car-info">
                            <h3>{{ relatedCar.marca }} {{ relatedCar.modelo }}</h3>
                            <p class="related-car-price">{{ formatPrice(relatedCar.precio) }} €</p>
                            <p class="related-car-specs">{{ relatedCar.year }} · {{ formatNumber(relatedCar.kilometros)
                            }} km · {{ relatedCar.combustible }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Pie de página -->
        <footer class="dashboard-footer">
            <p>© {{ currentYear }} VCars - Todos los derechos reservados</p>
        </footer>
    </div>
</template>

<script>
import axios from 'axios';
import { Link } from '@inertiajs/inertia-vue3';
import Swal from 'sweetalert2';

export default {
    components: { Link },
    name: "CarDetail",
    props: {
        id: {
            type: [Number, String],
            required: true
        }
    },
    data() {
        return {
            user: null,
            userLoading: true,
            showUserMenu: false,
            coche: {},
            loading: true,
            showFullDescription: false,
            financePeriod: "5",
            initialPayment: "1200",
            relatedCars: [],
            equipamiento: [],
            reservado: false // NUEVO
        };
    },
    computed: {
        currentYear() {
            return new Date().getFullYear();
        },
        calculatedMonthly() {
            if (!this.coche.precio) return "0";

            const loan = this.coche.precio - parseInt(this.initialPayment || 0);
            const months = parseInt(this.financePeriod) * 12;
            const interestRate = 0.0649 / 12; // Aproximadamente 6.49% anual

            const monthlyPayment = (loan * interestRate) / (1 - Math.pow(1 + interestRate, -months));

            return Math.round(monthlyPayment);
        }
    },
    mounted() {
        this.loadCoche();
        this.fetchUser();
        this.generateRelatedCars();
        this.generateEquipamiento();
    },
    methods: {
        traducirColor(color) {
            const colores = {
                "white": "Blanco",
                "black": "Negro",
                "red": "Rojo",
                "blue": "Azul",
                "green": "Verde",
                "yellow": "Amarillo",
                "gray": "Gris",
                "silver": "Plata",
                "orange": "Naranja",
                "brown": "Marrón",
                "beige": "Beige",
                "gold": "Dorado",
                "purple": "Morado",
                "pink": "Rosa",
                // Puedes añadir más colores según tus necesidades
            };
            if (!color) return "No especificado";
            const colorLower = color.toLowerCase();
            return colores[colorLower] || color;
        },
        async loadCoche() {
            this.loading = true;
            try {
                // Petición al back que ahora siempre devuelve JSON
                const response = await axios.get(`http://localhost:8000/api/cars/${this.id}`);
                const car = response.data;

                // DEBUG: ves el objeto completo en consola
                console.log("Datos recibidos de la API:", car);

                // Obtener la imagen principal del array photos
                let imagenPrincipal = '/images/car-placeholder.jpg';
                if (car.photos && car.photos.length > 0) {
                    imagenPrincipal = car.photos[0].url;
                }

                this.coche = {
                    id: car.id,
                    marca: car.make_name || 'Sin marca',
                    modelo: car.model_name || 'Sin modelo',
                    descripcion: car.description || `${car.fuel_type || ''} ${car.engine_power || ''}`.trim() || 'Sin descripción',
                    precio: car.price || 0,
                    cuota_mensual: car.monthly_payment || Math.ceil((car.price || 0) / 60),
                    year: car.year || new Date().getFullYear(),
                    kilometros: car.mileage || 0,
                    transmision: car.transmission_type === 'automatic' ? 'Automático' : 'Manual',
                    combustible: car.fuel_type || 'No especificado',
                    color: this.traducirColor(car.color),
                    potencia: car.engine_power || '',
                    plazas: car.seats || '',
                    puertas: car.doors || '',
                    imagen: imagenPrincipal,
                    descripcion_completa: car.full_description || '',
                    status: car.status || 'available'
                };

            } catch (error) {
                console.error("Error al cargar el coche:", error);
                this.mockCarDetail();
            } finally {
                this.loading = false;
            }
        },
        mockCarDetail() {
            // Datos de ejemplo para desarrollo
            this.coche = {
                id: this.id,
                marca: 'Renault',
                modelo: 'Twingo',
                descripcion: 'Intens Plus Energy',
                precio: 8495,
                cuota_mensual: 120,
                year: 2018,
                kilometros: 83000,
                transmision: 'Manual',
                combustible: 'Gasolina',
                color: 'Blanco',
                potencia: '71 CV',
                plazas: 5,
                puertas: 5,
                imagen: '/images/fiat-500.webp',
                status: 'sold' // <-- AÑADIDO AQUÍ PARA PRUEBA
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
        },
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
        formatPrice(price) {
            return new Intl.NumberFormat('es-ES').format(price);
        },
        formatNumber(number) {
            return new Intl.NumberFormat('es-ES').format(number);
        },
        generarDescripcion() {
            return `Este ${this.coche.marca} ${this.coche.modelo} ${this.coche.descripcion} del año ${this.coche.year} es una excelente opción para quienes buscan un vehículo compacto, eficiente y con un gran equipamiento. Con ${this.formatNumber(this.coche.kilometros)} kilómetros, este coche se encuentra en excelente estado tanto mecánico como estético.
  
  El motor de gasolina es potente y eficiente, permitiendo una conducción ágil tanto en ciudad como en carretera, con un consumo muy ajustado. La caja de cambios ${this.coche.transmision} de 5 velocidades ofrece una experiencia de conducción suave y precisa.
  
  Entre su equipamiento destacan elementos como aire acondicionado, bluetooth con control por voz, limitador de velocidad, ordenador de a bordo, radio/CD/MP3 con USB y AUX, volante multifunción, cierre centralizado, elevalunas eléctricos, espejos retrovisores eléctricos, y mucho más.
  
  Vehículo revisado en nuestro taller con libro de mantenimiento al día y garantía de 12 meses incluida en el precio.`;
        },
        generateRelatedCars() {
            // Coches relacionados de ejemplo
            this.relatedCars = [
                {
                    id: 2,
                    marca: 'Smart',
                    modelo: 'Forfour',
                    descripcion: '1.0 52kW (71CV) S/S',
                    precio: 8495,
                    year: 2018,
                    kilometros: 95159,
                    combustible: 'Gasolina',
                    imagen: '/images/polo-gti-7.jpg'
                },
                {
                    id: 3,
                    marca: 'Skoda',
                    modelo: 'Fabia',
                    descripcion: 'Combi 1.0 TSI 95CV',
                    precio: 8995,
                    year: 2018,
                    kilometros: 90167,
                    combustible: 'Gasolina',
                    imagen: '/images/fiat-500.webp'
                },
                {
                    id: 4,
                    marca: 'Fiat',
                    modelo: '500',
                    descripcion: 'Red 1.0 Hybrid 70CV',
                    precio: 10995,
                    year: 2021,
                    kilometros: 71576,
                    combustible: 'Híbrido',
                    imagen: '/images/polo-gti-7.jpg'
                }
            ];
        },
        generateEquipamiento() {
            // Equipamiento de ejemplo
            this.equipamiento = [
                'Aire acondicionado',
                'Bluetooth con control por voz',
                'Control de crucero / limitador',
                'Ordenador de a bordo',
                'Radio CD MP3 con USB y AUX',
                'Volante multifunción',
                'Cierre centralizado',
                'Elevalunas eléctricos',
                'Espejos retrovisores eléctricos',
                'Dirección asistida',
                'Isofix',
                'Start/Stop automático',
                'Sensor de luz y lluvia',
                'Llantas de aleación',
                'Control de estabilidad',
                'ABS',
                'Airbags frontales y laterales'
            ];
        },
        reservar() {
            this.reservado = true;
            Swal.fire({
                icon: 'success',
                title: '¡Reservado!',
                text: 'El vehículo ha sido reservado correctamente.'
            });
        }
    }
};
</script>

<style scoped>
/* Estilos generales */
.car-detail-container {
    font-family: Arial, sans-serif;
    color: #333;
    background: #fff;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* Barra de navegación - importada de tus estilos existentes */
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

.navbar .nav-links li:hover {
    color: #ffcc00;
}

.nav-links a {
    color: inherit;
    text-decoration: none;
}

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

.user-dropdown {
    position: absolute;
    top: 170%;
    left: 50%;
    transform: translateX(-50%);
    background: #fff;
    border: 1px solid #eee;
    border-radius: 10px;
    box-shadow: 0 8px 24px rgba(44, 62, 80, 0.18), 0 1.5px 6px rgba(0, 0, 0, 0.07);
    padding: 18px 0 10px 0;
    min-width: 170px;
    z-index: 100;
    animation: dropdownIn 0.22s cubic-bezier(.4, 0, .2, 1);
}

@keyframes dropdownIn {
    0% {
        opacity: 0;
        transform: translateY(-10px) scale(0.98);
    }

    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.reserve-btn:disabled {
    background-color: #bdbdbd !important;
    color: #fff !important;
    border: none !important;
    cursor: not-allowed !important;
    opacity: 0.8;
    box-shadow: none;
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
    box-shadow: -2px -2px 6px rgba(44, 62, 80, 0.08);
    transform: translateX(-50%) rotate(45deg);
    z-index: 101;
}

.logout-btn {
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

.logout-btn:hover {
    background: #ffcc00;
    color: #232526;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.18s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Contenido principal */
.car-detail-main {
    flex: 1;
    padding: 1.5rem 2rem;
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
}

/* Breadcrumb */
.breadcrumb {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 1.5rem;
}

.breadcrumb a {
    color: #666;
    text-decoration: none;
}

.breadcrumb a:hover {
    color: #ffcc00;
}

/* Detalle del coche - layout principal */
.detail-content {
    display: grid;
    grid-template-columns: 58% 40%;
    gap: 2%;
    margin-bottom: 2rem;
}

/* Galería de imágenes */
.car-gallery {
    width: 100%;
}

.main-image {
    position: relative;
    width: 100%;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 12px;
}

.main-image img {
    width: 100%;
    height: auto;
    display: block;
    border-radius: 8px;
}

.zoom-indicator {
    position: absolute;
    right: 12px;
    bottom: 12px;
    background-color: rgba(0, 0, 0, 0.6);
    color: white;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 0.9rem;
}

.thumbnail-row {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding-bottom: 10px;
}

.thumbnail {
    flex: 0 0 100px;
    height: 75px;
    border-radius: 6px;
    overflow: hidden;
    cursor: pointer;
    transition: opacity 0.2s;
    opacity: 0.7;
}

.thumbnail:hover {
    opacity: 1;
}

.thumbnail.active {
    opacity: 1;
    box-shadow: 0 0 0 2px #ffcc00;
}

.thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Información del coche */
.car-info {
    padding: 0 0 0 1rem;
}

.car-header h1 {
    font-size: 1.8rem;
    margin: 0 0 8px 0;
    color: #222;
}

.car-tagline {
    color: #666;
    margin: 0 0 1.5rem 0;
    font-size: 1.1rem;
}

.price-section {
    margin-bottom: 1.5rem;
}

.price-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.8rem;
}

.current-price {
    font-size: 2rem;
    font-weight: bold;
    color: #222;
}

.share-btn {
    background: none;
    border: 1px solid #ddd;
    border-radius: 50%;
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #666;
    transition: all 0.2s;
}

.share-btn:hover {
    background: #f5f5f5;
    color: #333;
}

.financing {
    display: flex;
    align-items: center;
    background-color: #f5f5f5;
    padding: 12px 15px;
    border-radius: 6px;
}

.finance-label {
    color: #666;
    margin-right: 10px;
}

.finance-price {
    font-weight: bold;
    color: #222;
    font-size: 1.2rem;
    margin-right: auto;
}

.calculate-btn {
    background: none;
    border: none;
    color: #0066cc;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    font-weight: 600;
    padding: 5px 10px;
    transition: color 0.2s;
}

.calculate-btn:hover {
    color: #004c99;
}

/* Botones de acción */
.cta-buttons {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 2rem;
}

.reserve-btn {
    grid-column: 1 / -1;
    background-color: #ffcc00;
    color: #222;
    border: none;
    border-radius: 6px;
    padding: 14px;
    font-size: 1.1rem;
    font-weight: bold;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background-color 0.2s;
}

.reserve-btn:hover {
    background-color: #ffbb00;
}

.call-btn {
    background-color: #34a853;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 10px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
    transition: background-color 0.2s;
}

.call-btn:hover {
    background-color: #2d9348;
}

.call-btn span {
    font-size: 0.85rem;
    font-weight: normal;
}

.whatsapp-btn {
    background-color: #25d366;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 10px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background-color 0.2s;
}

.whatsapp-btn:hover {
    background-color: #1da851;
}

/* Calculadora financiera */
.finance-calculator {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.finance-calculator h3 {
    margin-top: 0;
    margin-bottom: 1rem;
    font-size: 1.2rem;
}

.calculator-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.form-row {
    display: flex;
    gap: 1rem;
}

.form-group {
    flex: 1;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-size: 0.9rem;
    color: #666;
}

.radio-options {
    display: flex;
    gap: 10px;
}

.radio-option {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.radio-option input {
    margin-right: 5px;
}

.price-input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

.calc-result {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    background-color: #fff;
    border-radius: 4px;
    margin-top: 0.5rem;
}

.monthly-result {
    font-weight: bold;
    font-size: 1.2rem;
    color: #222;
}

.finance-disclaimer {
    font-size: 0.8rem;
    color: #999;
    margin-top: 0.5rem;
}

/* Características técnicas */
.car-specs {
    margin-bottom: 2rem;
}

.car-specs h2 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: #222;
}

.specs-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1rem;
}

.spec-item {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    flex-direction: column;
}

.spec-label {
    font-size: 0.8rem;
    color: #666;
    margin-bottom: 0.5rem;
}

.spec-value {
    font-weight: bold;
    color: #222;
}

/* Descripción */
.car-description {
    margin-bottom: 2rem;
}

.car-description h2 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: #222;
}

.description-content {
    line-height: 1.6;
    max-height: 200px;
    overflow: hidden;
    transition: max-height 0.3s;
}

.description-content.expanded {
    max-height: none;
}

.show-more-btn {
    background: none;
    border: none;
    color: #0066cc;
    cursor: pointer;
    padding: 5px 0;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 0.5rem;
}

.show-more-btn:hover {
    color: #004c99;
}

/* Equipamiento */
.car-equipment {
    margin-bottom: 2rem;
}

.car-equipment h2 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: #222;
}

.equipment-list {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.5rem 1rem;
}

.equipment-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.equipment-item i {
    color: #34a853;
}

/* Video */
.car-video {
    margin-bottom: 2rem;
}

.car-video h2 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: #222;
}

.video-container {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%;
    /* 16:9 aspect ratio */
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
}

.video-thumbnail {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.play-button {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 80px;
    height: 80px;
    background-color: rgba(0, 0, 0, 0.7);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    transition: background-color 0.2s;
}

.play-button:hover {
    background-color: rgba(0, 0, 0, 0.85);
}

/* Coches relacionados */
.related-cars {
    margin-bottom: 2rem;
}

.related-cars h2 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: #222;
}

.related-cars-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

.related-car-card {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.related-car-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.related-car-card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.related-car-info {
    padding: 1rem;
}

.related-car-info h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
}

.related-car-price {
    font-weight: bold;
    color: #222;
    margin: 0 0 0.5rem 0;
}

.related-car-specs {
    font-size: 0.9rem;
    color: #666;
    margin: 0;
}

/* Footer */
.dashboard-footer {
    background-color: #000;
    color: #fff;
    padding: 1.5rem 2rem;
    text-align: center;
    margin-top: 2rem;
}

/* Responsive */
@media (max-width: 992px) {
    .detail-content {
        grid-template-columns: 1fr;
    }

    .car-info {
        padding: 1rem 0 0 0;
    }

    .specs-grid {
        grid-template-columns: repeat(3, 1fr);
    }

    .equipment-list {
        grid-template-columns: repeat(2, 1fr);
    }

    .related-cars-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .specs-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .equipment-list {
        grid-template-columns: 1fr;
    }

    .related-cars-grid {
        grid-template-columns: 1fr;
    }

    .cta-buttons {
        grid-template-columns: 1fr;
    }
}

.reservado-label {
    position: absolute;
    top: 16px;
    left: 16px;
    background: #ff3333;
    color: #fff;
    font-weight: bold;
    font-size: 1.1rem;
    padding: 8px 18px;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    z-index: 10;
    letter-spacing: 1px;
}
</style>