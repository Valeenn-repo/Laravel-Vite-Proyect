<template>
    <div class="dashboard-container">
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
                        <!-- Puedes poner un spinner aquí si lo deseas -->
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
        <main class="vehiculos-main">
            <!-- Sección de filtros -->
            <div class="filters-section">
                <div class="filters-container">
                    <div class="filter-row">
                        <div class="filter-item">
                            <select v-model="filters.marca">
                                <option value="" disabled selected>Marca</option>
                                <option v-for="marca in marcas" :key="marca.id" :value="marca.id">
                                    {{ marca.name }}
                                </option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <select v-model="filters.modelo" :disabled="!filters.marca">
                                <option value="" disabled selected>Modelo</option>
                                <option v-for="modelo in getModelosByMarca" :key="modelo.id" :value="modelo.id">
                                    {{ modelo.name }}
                                </option>
                            </select>
                        </div>
                        <!-- <div class="filter-item">
                            <select v-model="filters.carroceria">
                                <option value="" disabled selected>Carrocería</option>
                                <option value="berlina">Berlina</option>
                                <option value="suv">SUV</option>
                                <option value="hatchback">Hatchback</option>
                                <option value="familiar">Familiar</option>
                                <option value="coupe">Coupé</option>
                            </select>
                        </div> -->
                        <div class="filter-item">
                            <select v-model="filters.combustible">
                                <option value="" disabled selected>Combustible</option>
                                <option value="gasolina">Gasolina</option>
                                <option value="diésel">Diésel</option>
                                <option value="híbrido">Híbrido</option>
                                <option value="eléctrico">Eléctrico</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <select v-model="filters.cambio">
                                <option value="" disabled selected>Cambio</option>
                                <option value="manual">Manual</option>
                                <option value="automatico">Automático</option>
                            </select>
                        </div>
                    </div>
                    <div class="filter-row">
                        <div class="filter-item">
                            <select v-model="filters.cuota">
                                <option value="" disabled selected>Cuota desde</option>
                                <option value="100">Desde 100€/mes</option>
                                <option value="150">Desde 150€/mes</option>
                                <option value="200">Desde 200€/mes</option>
                                <option value="300">Desde 300€/mes</option>
                                <option value="400">Desde 400€/mes</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <select v-model="filters.kilometros">
                                <option value="" disabled selected>Kilómetros</option>
                                <option value="10000">Menos de 10.000 km</option>
                                <option value="50000">Menos de 50.000 km</option>
                                <option value="100000">Menos de 100.000 km</option>
                                <option value="150000">Menos de 150.000 km</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <select v-model="filters.equipamiento">
                                <option value="" disabled selected>Equipamiento</option>
                                <option value="ac">Aire acondicionado</option>
                                <option value="gps">Navegador GPS</option>
                                <option value="camera">Cámara trasera</option>
                                <option value="leather">Asientos de cuero</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <select v-model="filters.traccion">
                                <option value="" disabled selected>Tracción</option>
                                <option value="delantera">Delantera</option>
                                <option value="trasera">Trasera</option>
                                <option value="integral">4x4/Integral</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <select v-model="filters.color">
                                <option value="" disabled selected>Color</option>
                                <option value="white">Blanco</option>
                                <option value="black">Negro</option>
                                <option value="grey">Gris/Plata</option>
                                <option value="red">Rojo</option>
                                <option value="blue">Azul</option>
                                <option value="green">Verde</option>
                                <option value="yellow">Amarillo</option>
                                <option value="purple">Violeta</option>
                            </select>
                        </div>
                    </div>
                    <div class="filter-actions">
                        <button class="clean-btn" @click="limpiarFiltros">Limpiar todo</button>
                    </div>
                </div>
            </div>

            <!-- Sección de ordenación -->
            <div class="sort-section">
                <div class="results-count">Mostrando {{ vehiculos.length }} vehículos</div>
                <div class="sort-container">
                    <span>Ordenar por:</span>
                    <select v-model="sortBy" @change="sortVehiculos">
                        <option value="precio_asc">Precio: Ascendente</option>
                        <option value="precio_desc">Precio: Descendente</option>
                        <option value="km_asc">Kilómetros: Ascendente</option>
                        <option value="km_desc">Kilómetros: Descendente</option>
                        <option value="year_desc">Año: Más reciente</option>
                        <option value="year_asc">Año: Más antiguo</option>
                    </select>
                </div>
            </div>

            <!-- Listado de vehículos -->
            <div class="vehicles-list">
                <div v-for="coche in vehiculosFiltrados" :key="coche.id" class="vehicle-card">
                    <div class="card-img">
                        <img :src="coche.imagen || '/images/car-placeholder.jpg'"
                            :alt="coche.marca + ' ' + coche.modelo">
                        <div v-if="coche.status === 'sold'" class="sold-badge">VENDIDO</div>
                    </div>
                    <div class="card-content">
                        <h3 class="car-title">{{ coche.marca }} {{ coche.modelo }} {{ coche.descripcion }}</h3>
                        <div class="price-container">
                            <div class="price">{{ formatPrice(coche.precio) }} €</div>
                            <div class="monthly-price">Desde {{ coche.cuota_mensual }} €/mes</div>
                        </div>
                        <div class="car-details">
                            <div class="detail-item">
                                <span class="year">{{ coche.year }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="km">{{ formatNumber(coche.kilometros) }} km</span>
                            </div>
                            <div class="detail-item">
                                <span class="transmission">{{ coche.transmision }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-actions">
                        <button class="details-btn" @click="verDetalles(coche.id)">Ver detalles</button>
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

export default {
    components: { Link },
    name: "Vehiculos",
    props: {
        initialFilters: {
            type: Object,
            default: () => ({
                marca: "",
                modelo: ""
            })
        }
    },
    data() {
        return {
            user: null,
            userLoading: true,
            showUserMenu: false,
            marcas: [],
            vehiculos: [],
            vehiculosFiltrados: [],
            filters: {
                marca: this.initialFilters?.marca || "",
                modelo: this.initialFilters?.modelo || "",
                carroceria: "",
                combustible: "",
                cambio: "",
                cuota: "",
                kilometros: "",
                equipamiento: "",
                traccion: "",
                color: ""
            },
            sortBy: "precio_asc",
            showMoreFilters: false,
            selectedCars: [],
            loading: false
        };
    },
    mounted() {
        this.loadMarcas();
        this.loadVehiculos();
        this.fetchUser();
    },
    computed: {
        currentYear() {
            return new Date().getFullYear();
        },
        getModelosByMarca() {
            if (!this.filters.marca) return [];

            const marca = this.marcas.find(m => m.id === this.filters.marca);
            return marca ? marca.models : [];
        }
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
        async loadMarcas() {
            try {
                const response = await axios.get("http://localhost:8000/api/car-data");
                this.marcas = response.data;
            } catch (error) {
                console.error("Error al cargar marcas y modelos", error);
            }
        },
        async loadVehiculos() {
    try {
        // Mostrar indicador de carga
        this.loading = true;
        
        // Llamada a la API para obtener los vehículos
        const response = await axios.get("http://localhost:8000/cars");
        
        if (response.data && Array.isArray(response.data)) {
            console.log("Datos recibidos de la API:", response.data.slice(0, 2)); // Muestra los primeros 2 elementos para debug
            
            // Mapeamos los datos para el formato que necesitamos
            this.vehiculos = response.data.map(car => {
                // Valores por defecto para manejar campos nulos o indefinidos
                const make = car.make_name || 'Sin marca';
                const model = car.model_name || 'Sin modelo';
                const price = car.price || 0;
                
                // Calcula la cuota mensual estimada (esto es un ejemplo, ajústalo según tus reglas de negocio)
                const cuotaMensual = Math.ceil(price / 60); // Suponiendo financiación a 60 meses
                
                return {
                    id: car.id,
                    marca: make,
                    modelo: model,
                    descripcion: car.description || `${car.fuel_type || ''} ${car.engine_power || ''}`.trim() || 'Sin descripción',
                    precio: price,
                    cuota_mensual: car.monthly_payment || cuotaMensual,
                    year: car.year || new Date().getFullYear(),
                    kilometros: car.mileage || 0,
                    transmision: car.transmission_type === 'automatic' ? 'Automático' : 'Manual',
                    combustible: car.fuel_type || 'No especificado',
                    color: car.color || 'No especificado',
                    imagen: car.image_url || null,
                    selected: false,
                    status: car.status || 'available', // <-- AÑADIDO: Asegúrate de que el status se mapea
                    // Propiedades adicionales con valores por defecto
                    carroceria: car.body_type || 'No especificado',
                    equipamiento: car.equipment ? car.equipment.split(',') : []
                };
            });
            
            // Verificación rápida de que tenemos datos
            console.log(`Se cargaron ${this.vehiculos.length} vehículos de la base de datos`);
        } else {
            console.error("La API no devolvió un array de vehículos:", response.data);
            // Si no hay datos válidos, cargamos datos de ejemplo para desarrollo
            this.mockVehiculos();
        }
    } catch (error) {
        console.error("Error al cargar vehículos:", error);
        
        if (error.response) {
            // El servidor respondió con un código de estado fuera del rango 2xx
            console.error("Respuesta del servidor:", error.response.data);
            console.error("Estado HTTP:", error.response.status);
        } else if (error.request) {
            // La solicitud se realizó pero no se recibió respuesta
            console.error("No se recibió respuesta del servidor");
        } else {
            // Algo sucedió en la configuración de la solicitud que desencadenó un error
            console.error("Error al configurar la solicitud:", error.message);
        }
        
        // Cargar datos de ejemplo para que la interfaz siga funcionando
        this.mockVehiculos();
    } finally {
        // Aplica filtros y ordenación a los datos cargados
        this.applyFiltersAndSort();
        
        // Ocultar indicador de carga
        this.loading = false;
    }
},
        mockVehiculos() {
            // Datos de ejemplo para desarrollo
            this.vehiculos = [
                {
                    id: 1,
                    marca: 'Renault',
                    modelo: 'Twingo',
                    descripcion: 'intens plus energy',
                    precio: 8495,
                    cuota_mensual: 120,
                    year: 2018,
                    kilometros: 83000,
                    transmision: 'Manual',
                    combustible: 'Gasolina',
                    color: 'Blanco',
                    imagen: '/images/fiat-500.webp',
                    selected: false,
                    status: 'sold' // <-- AÑADIDO: Ejemplo de coche vendido
                },
                {
                    id: 2,
                    marca: 'Smart',
                    modelo: 'Forfour',
                    descripcion: '1.0 52kW (71CV) S/S',
                    precio: 8495,
                    cuota_mensual: 156,
                    year: 2018,
                    kilometros: 95159,
                    transmision: 'Manual',
                    combustible: 'Gasolina',
                    color: 'Gris',
                    imagen: '/images/polo-gti-7.jpg',
                    selected: false,
                    status: 'available' // <-- AÑADIDO: Ejemplo de coche disponible
                },
                {
                    id: 3,
                    marca: 'Skoda',
                    modelo: 'Fabia',
                    descripcion: 'Combi 1.0 TSI 95CV Ambition',
                    precio: 8995,
                    cuota_mensual: 163,
                    year: 2018,
                    kilometros: 90167,
                    transmision: 'Manual',
                    combustible: 'Gasolina',
                    color: 'Azul',
                    imagen: '/images/fiat-500.webp',
                    selected: false,
                    status: 'available'
                },
                {
                    id: 4,
                    marca: 'Fiat',
                    modelo: '500',
                    descripcion: 'Red 1.0 Hybrid 51KW (70CV)',
                    precio: 10995,
                    cuota_mensual: 154,
                    year: 2021,
                    kilometros: 71576,
                    transmision: 'Manual',
                    combustible: 'Híbrido',
                    color: 'Rojo',
                    imagen: '/images/polo-gti-7.jpg',
                    selected: false,
                    status: 'sold' // <-- AÑADIDO: Otro ejemplo de coche vendido
                }
            ];
            this.applyFiltersAndSort();
        },
        applyFiltersAndSort() {
            // Filtrar vehículos según los criterios seleccionados
            let filtered = [...this.vehiculos];

            // Aplicar los filtros activos
            if (this.filters.marca) {
                const marcaSeleccionada = this.marcas.find(m => m.id === this.filters.marca);
                if (marcaSeleccionada) {
                    filtered = filtered.filter(car => car.marca === marcaSeleccionada.name);
                }
            }

            if (this.filters.modelo) {
                const modeloSeleccionado = this.getModelosByMarca.find(m => m.id === this.filters.modelo);
                if (modeloSeleccionado) {
                    filtered = filtered.filter(car => car.modelo === modeloSeleccionado.name);
                }
            }

            if (this.filters.combustible) {
                filtered = filtered.filter(car => car.combustible && car.combustible.toLowerCase().includes(this.filters.combustible));
            }

            if (this.filters.cambio) {
                const tipoCambio = this.filters.cambio === 'automatico' ? 'Automático' : 'Manual';
                filtered = filtered.filter(car => car.transmision === tipoCambio);
            }

            if (this.filters.color) {
                filtered = filtered.filter(car => car.color && car.color.toLowerCase().includes(this.filters.color));
            }

            if (this.filters.kilometros) {
                const maxKm = parseInt(this.filters.kilometros);
                filtered = filtered.filter(car => car.kilometros <= maxKm);
            }

            if (this.filters.cuota) {
                const minCuota = parseInt(this.filters.cuota);
                filtered = filtered.filter(car => car.cuota_mensual >= minCuota);
            }

            // Ordenar vehículos
            this.sortVehiculosArray(filtered);

            this.vehiculosFiltrados = filtered;
        },
        sortVehiculosArray(array) {
            switch (this.sortBy) {
                case 'precio_asc':
                    array.sort((a, b) => a.precio - b.precio);
                    break;
                case 'precio_desc':
                    array.sort((a, b) => b.precio - a.precio);
                    break;
                case 'km_asc':
                    array.sort((a, b) => a.kilometros - b.kilometros);
                    break;
                case 'km_desc':
                    array.sort((a, b) => b.kilometros - a.kilometros);
                    break;
                case 'year_asc':
                    array.sort((a, b) => a.year - b.year);
                    break;
                case 'year_desc':
                    array.sort((a, b) => b.year - a.year);
                    break;
            }
        },
        sortVehiculos() {
            this.applyFiltersAndSort();
        },
        limpiarFiltros() {
            for (const key in this.filters) {
                this.filters[key] = "";
            }
            this.applyFiltersAndSort();
        },
        toggleMoreFilters() {
            this.showMoreFilters = !this.showMoreFilters;
        },
        toggleCarSelection(coche) {
            if (coche.selected) {
                this.selectedCars.push(coche.id);
            } else {
                const index = this.selectedCars.indexOf(coche.id);
                if (index > -1) {
                    this.selectedCars.splice(index, 1);
                }
            }
        },
        compararVehiculos() {
            if (this.selectedCars.length >= 2) {
                // Redirigir a la página de comparación con los IDs seleccionados
                const ids = this.selectedCars.join(',');
                window.location.href = `/comparar-vehiculos?ids=${ids}`;
            }
        },
        verDetalles(id) {
            // Redirigir a la página de detalles del vehículo
            window.location.href = `/cars/${id}`;
        },
        formatPrice(price) {
            return new Intl.NumberFormat('es-ES').format(price);
        },
        formatNumber(number) {
            return new Intl.NumberFormat('es-ES').format(number);
        }
    },
    watch: {
        'filters': {
            handler() {
                this.applyFiltersAndSort();
            },
            deep: true
        }
    }
};
</script>

<style scoped>
/* Estilos generales */
.dashboard-container {
    font-family: Arial, sans-serif;
    color: #333;
    background: #fff;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* ====== ENCABEZADO ====== */
.dashboard-header {
    position: relative;
    background-color: #000;
    width: 100%;
    margin: 0;
    padding: 0;
}

/* Barra de navegación */
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

.nav-links a.active {
    color: #ffcc00;
    font-weight: bold;
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

.user-info i {
    font-size: 1.1rem;
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

.user-dropdown .logout-btn {
    width: 100%;
    background: none;
    color: #232526;
    border: none;
    border-radius: 0 0 10px 10px;
    font-weight: 600;
    margin-top: 4px;
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

/* ====== CONTENIDO PRINCIPAL ====== */
.vehiculos-main {
    flex: 1;
    padding: 2rem;
    background-color: #f5f7fa;
}

/* Sección de filtros */
.filters-section {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.filters-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.filter-row {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.filter-item {
    flex: 1;
    min-width: 150px;
}

.filter-item select {
    width: 100%;
    padding: 0.7rem 1rem;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    font-size: 0.9rem;
    background-color: #fff;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.7rem center;
    background-size: 1rem;
}

.filter-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
}

.clean-btn,
.more-filters-btn,
.compare-btn {
    padding: 0.7rem 1.2rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s;
}

.clean-btn {
    background: none;
    border: 1px solid #e0e0e0;
    color: #555;
}

.clean-btn:hover {
    background: #f9f9f9;
}

.more-filters-btn {
    background: none;
    border: 1px solid #ffcc00;
    color: #333;
}

.more-filters-btn:hover {
    background: #fff6d9;
}

.compare-btn {
    background: #ffcc00;
    border: 1px solid #ffcc00;
    color: #333;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.compare-btn:hover {
    background: #ffbb00;
}

.compare-btn:disabled {
    background: #f0f0f0;
    border-color: #e0e0e0;
    color: #999;
    cursor: not-allowed;
}

/* Sección de ordenación */
.sort-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding: 0 0.5rem;
}

.results-count {
    font-size: 0.9rem;
    color: #666;
}

.sort-container {
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.sort-container span {
    font-size: 0.9rem;
    color: #666;
}

.sort-container select {
    padding: 0.5rem 2rem 0.5rem 0.8rem;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    font-size: 0.9rem;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.5rem center;
    background-size: 0.9rem;
}

/* Lista de vehículos */
.vehicles-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.vehicle-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
}

.vehicle-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
}

.card-img {
    position: relative; /* Necesario para el badge de "Vendido" */
    width: 100%;
    padding-top: 66.66%; /* Aspect ratio 3:2 (height = 2/3 * width) */
    overflow: hidden;
}

.card-img img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover; /* Asegura que la imagen cubra el área sin distorsionarse */
}

.sold-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background-color: #dc3545;
    color: white;
    padding: 6px 12px;
    font-size: 0.85em;
    font-weight: bold;
    border-radius: 4px;
    z-index: 1000;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

.card-content {
    padding: 1.2rem;
}

.car-title {
    font-size: 1.1rem;
    margin: 0 0 0.8rem;
    line-height: 1.3;
    height: 2.8rem;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.price-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.price {
    font-size: 1.4rem;
    font-weight: 700;
    color: #333;
}

.monthly-price {
    font-size: 0.9rem;
    color: #666;
    font-weight: 500;
}

.car-details {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    margin-bottom: 1rem;
    border-top: 1px solid #f0f0f0;
    padding-top: 0.8rem;
}

.detail-item {
    flex: 1;
    min-width: 30%;
    text-align: center;
    font-size: 0.85rem;
    color: #666;
    padding: 0.5rem 0;
}

.year,
.km,
.transmission {
    display: block;
    font-weight: 600;
    color: #444;
}

.card-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f9f9f9;
    padding: 1rem;
    border-top: 1px solid #eee;
}

.select-container {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.select-container input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: #ffcc00;
}

.select-container label {
    font-size: 0.9rem;
    color: #555;
    cursor: pointer;
}

.details-btn {
    padding: 0.6rem 1rem;
    background: #ffcc00;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.9rem;
    color: #333;
    cursor: pointer;
    transition: background 0.2s;
}

.details-btn:hover {
    background: #ffbb00;
}

/* Pie de página */
.dashboard-footer {
    background-color: #000;
    text-align: center;
    padding: 1.5rem;
    font-size: 0.9rem;
    color: #fff;
}

/* Estilos de transición para el menú desplegable */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.18s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Estilos responsive */
@media (max-width: 1024px) {
    .filter-row {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .navbar {
        flex-direction: column;
        padding: 1rem;
    }

    .navbar .nav-links {
        margin-top: 1rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .navbar .nav-links li {
        margin: 0.5rem 1rem;
    }

    .filter-row {
        grid-template-columns: repeat(2, 1fr);
    }

    .vehicles-list {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
}

@media (max-width: 576px) {
    .filter-row {
        grid-template-columns: 1fr;
    }

    .sort-section {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .vehicles-list {
        grid-template-columns: 1fr;
    }
}

/* Estilos para las ventanas modales si se utilizan */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-container {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    max-width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    padding: 2rem;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #666;
}

.modal-body {
    margin-bottom: 1.5rem;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}
</style>