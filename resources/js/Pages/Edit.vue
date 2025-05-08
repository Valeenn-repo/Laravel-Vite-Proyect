<template>
    <div class="edit-vehicle-container">
        <h2>Editar Vehículo</h2>

        <div v-if="loading" class="loading">Cargando datos del vehículo...</div>

        <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>

        <form v-if="!loading && vehicle" @submit.prevent="updateVehicle" class="vehicle-form">
            <div class="form-grid">
                <div class="form-group">
                    <label for="make">Marca:</label>
                    <select id="make" v-model="vehicle.make_id" class="form-control" @change="onMakeChange" required>
                        <option v-for="make in makes" :key="make.id" :value="make.id">{{ make.name }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="model">Modelo:</label>
                    <input type="text" id="model" v-model="vehicle.model_name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="year">Año:</label>
                    <input type="number" id="year" v-model="vehicle.year" class="form-control" min="1900" max="2100"
                        required>
                </div>

                <div class="form-group">
                    <label for="price">Precio (€):</label>
                    <input type="number" id="price" v-model="vehicle.price" class="form-control" min="0" step="0.01"
                        required>
                </div>

                <div class="form-group">
                    <label for="vin">VIN:</label>
                    <input type="text" id="vin" v-model="vehicle.vin" class="form-control" maxlength="17" minlength="17"
                        required>
                </div>

                <div class="form-group">
                    <label for="mileage">Kilometraje:</label>
                    <input type="number" id="mileage" v-model="vehicle.mileage" class="form-control" min="0">
                </div>

                <div class="form-group">
                    <label for="color">Color:</label>
                    <input type="text" id="color" v-model="vehicle.color" class="form-control">
                </div>

                <div class="form-group">
                    <label for="transmission">Transmisión:</label>
                    <select id="transmission" v-model="vehicle.transmission_type" class="form-control">
                        <option value="">-- Selecciona --</option>
                        <option value="manual">Manual</option>
                        <option value="automatic">Automática</option>
                        <option value="semi-automatic">Semi-automática</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fuel">Combustible:</label>
                    <select id="fuel" v-model="vehicle.fuel_type" class="form-control">
                        <option value="">-- Selecciona --</option>
                        <option value="gasolina">Gasolina</option>
                        <option value="diésel">Diésel</option>
                        <option value="eléctrico">Eléctrico</option>
                        <option value="híbrido">Híbrido</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="dealership">Concesionario:</label>
                    <select id="dealership" v-model="vehicle.dealership_id" class="form-control" required>
                        <option v-for="dealership in dealerships" :key="dealership.id" :value="dealership.id">
                            {{ dealership.name }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Estado:</label>
                    <select id="status" v-model="vehicle.status" class="form-control" required>
                        <option value="available">Disponible</option>
                        <option value="reserved">Reservado</option>
                        <option value="sold">Vendido</option>
                    </select>
                </div>
            </div>

            <!-- Simplificación de la sección de imagen -->
            <div class="image-section">
                <h3>Imagen del vehículo</h3>

                <div v-if="vehicleImage" class="vehicle-image-container">
                    <img :src="vehicleImage.url" :alt="`Imagen del vehículo ${vehicle.make_name} ${vehicle.model_name}`"
                        class="vehicle-image">
                </div>

                <div v-else class="no-image">
                    No hay imagen asociada a este vehículo.
                </div>

                <div class="update-image-section">
                    <div class="form-group">
                        <label for="image_url">URL de la imagen:</label>
                        <input type="url" id="image_url" v-model="imageUrl" class="form-control"
                            placeholder="https://ejemplo.com/imagen.jpg">
                    </div>
                    <button type="button" @click="updateImage" class="update-image-btn" :disabled="!imageUrl">
                        {{ vehicleImage ? 'Actualizar imagen' : 'Añadir imagen' }}
                    </button>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="save-btn" :disabled="saving">
                    <span v-if="saving">Guardando...</span>
                    <span v-else>Guardar cambios</span>
                </button>
                <Link href="/vehiculos/edit" class="cancel-btn">Cancelar</Link>
            </div>
        </form>

        <div v-if="!loading && !vehicle" class="not-found">
            Vehículo no encontrado o ha sido eliminado.
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { Inertia } from '@inertiajs/inertia';
import { Link } from '@inertiajs/inertia-vue3';

export default {
    name: 'EditVehicle',
    components: {
        Link
    },
    // Add props to accept the vehicle ID from Inertia
    props: {
        id: {
            type: [String, Number],
            required: true
        }
    },
    data() {
        return {
            vehicle: null,
            vehicleImage: null,
            imageUrl: '',
            makes: [],
            dealerships: [],
            loading: true,
            saving: false,
            errorMessage: '',
            vehicleId: null
        };
    },
    created() {
        // Use the ID from props instead of route params
        this.vehicleId = this.id;
        this.loadVehicle();
        this.loadMakes();
        this.loadDealerships();
    },
    methods: {
        async loadVehicle() {
            this.loading = true;
            this.errorMessage = '';

            try {
                const response = await axios.get(`http://localhost:8000/api/cars/${this.vehicleId}`);
                this.vehicle = response.data;

                // Obtener la primera imagen (si existe) para simplificar
                if (this.vehicle.photos && this.vehicle.photos.length > 0) {
                    this.vehicleImage = this.vehicle.photos[0];
                    this.imageUrl = this.vehicleImage.url;
                } else {
                    this.vehicleImage = null;
                    this.imageUrl = '';
                }
            } catch (error) {
                console.error('Error cargando vehículo:', error);
                this.errorMessage = 'Error al cargar los datos del vehículo.';
            } finally {
                this.loading = false;
            }
        },
        async loadMakes() {
            try {
                // Cambia el endpoint a car-data, igual que en AddVehicle.vue
                const response = await axios.get('http://localhost:8000/api/car-data');
                // Si la respuesta tiene .makes, úsalo, si no, usa el array directamente
                this.makes = response.data.makes || response.data;
            } catch (error) {
                console.error('Error cargando marcas:', error);
            }
        },
        async loadDealerships() {
            try {
                const response = await axios.get('http://localhost:8000/api/dealerships');
                this.dealerships = response.data;
            } catch (error) {
                console.error('Error cargando concesionarios:', error);
            }
        },
        onMakeChange() {
            // Si cambia la marca, reseteamos el modelo
            this.vehicle.model_name = '';
        },
        async updateVehicle() {
            this.saving = true;
            this.errorMessage = '';

            try {
                // Preparar los datos del vehículo para el envío
                const vehicleData = {
                    make_id: this.vehicle.make_id,
                    modelo: this.vehicle.model_name, // <-- CORREGIDO
                    year: this.vehicle.year,
                    price: this.vehicle.price,
                    vin: this.vehicle.vin,
                    mileage: this.vehicle.mileage,
                    color: this.vehicle.color,
                    transmission_type: this.vehicle.transmission_type,
                    fuel_type: this.vehicle.fuel_type,
                    dealership_id: this.vehicle.dealership_id,
                    status: this.vehicle.status
                };

                await axios.put(`http://localhost:8000/api/cars/${this.vehicleId}`, vehicleData);

                // Redirigir a la lista con mensaje de éxito
                Inertia.get('/vehiculos', {
                    success: `Vehículo ${this.vehicle.make_name} ${this.vehicle.model_name} actualizado correctamente`
                });

            } catch (error) {
                console.error('Error actualizando vehículo:', error);


                if (error.response && error.response.data) {
                    console.error('Respuesta del servidor:', error.response.data);
                    this.errorMessage = error.response.data.message || error.response.data.error || 'Error del servidor';
                } else {
                    this.errorMessage = 'Error al actualizar el vehículo.';
                }

                this.saving = false;
            }
        },
        async updateImage() {
            if (!this.imageUrl) return;

            try {
                if (this.vehicleImage) {
                    // Actualizar imagen existente
                    await axios.put(`http://localhost:8000/api/car-images/${this.vehicleImage.id}`, {
                        car_id: this.vehicleId,
                        image_url: this.imageUrl
                    });

                    // Actualizar datos locales
                    this.vehicleImage.url = this.imageUrl;
                } else {
                    // Añadir nueva imagen
                    const response = await axios.post('http://localhost:8000/api/car-images', {
                        car_id: this.vehicleId,
                        image_url: this.imageUrl,
                        is_primary: true
                    });

                    // Actualizar datos locales
                    this.vehicleImage = {
                        id: response.data.id,
                        url: this.imageUrl,
                        is_primary: true
                    };
                }

                this.errorMessage = '';

            } catch (error) {
                console.error('Error actualizando imagen:', error);
                this.errorMessage = 'Error al actualizar la imagen del vehículo.';
            }
        }
    }
};
</script>

<style scoped>
.edit-vehicle-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}

h2 {
    text-align: center;
    margin-bottom: 2rem;
}

h3 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    border-bottom: 1px solid #eee;
    padding-bottom: 0.5rem;
}

.vehicle-form {
    background-color: #fff;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.form-group {
    margin-bottom: 1rem;
}

label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.form-control {
    width: 100%;
    padding: 0.7rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

.form-control:focus {
    border-color: #ffcc00;
    outline: none;
    box-shadow: 0 0 0 2px rgba(255, 204, 0, 0.2);
}

.image-section {
    margin-top: 2rem;
    padding: 1.5rem;
    background-color: #f9f9f9;
    border-radius: 8px;
}

.vehicle-image-container {
    display: flex;
    justify-content: center;
    margin-bottom: 1.5rem;
}

.vehicle-image {
    max-width: 100%;
    max-height: 300px;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.no-image {
    text-align: center;
    padding: 2rem;
    background-color: #f5f5f5;
    border-radius: 8px;
    color: #666;
    font-style: italic;
    margin-bottom: 1.5rem;
}

.update-image-section {
    margin-top: 1.5rem;
}

.update-image-btn {
    background-color: #e6f7e6;
    color: #2e7d32;
    border: none;
    padding: 0.6rem 1.2rem;
    border-radius: 4px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s;
    margin-top: 0.5rem;
}

.update-image-btn:hover {
    background-color: #c8e6c9;
}

.update-image-btn:disabled {
    background-color: #f0f0f0;
    color: #999;
    cursor: not-allowed;
}

.form-actions {
    margin-top: 2rem;
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}

.save-btn,
.cancel-btn {
    padding: 0.8rem 1.5rem;
    border-radius: 4px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
}

.save-btn {
    background-color: #ffcc00;
    color: #232526;
    border: none;
    transition: background-color 0.2s;
}

.save-btn:hover {
    background-color: #ffbb00;
}

.save-btn:disabled {
    background-color: #f0f0f0;
    color: #999;
    cursor: not-allowed;
}

.cancel-btn {
    background-color: #e0e0e0;
    color: #424242;
    border: none;
}

.cancel-btn:hover {
    background-color: #d5d5d5;
}

.loading,
.not-found {
    text-align: center;
    padding: 3rem;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.error-message {
    padding: 1rem;
    border-radius: 4px;
    margin-bottom: 1rem;
    text-align: center;
    background-color: #fee0e0;
    color: #d32f2f;
    border: 1px solid #ffcdd2;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }

    .vehicle-form {
        padding: 1.5rem;
    }

    .form-actions {
        flex-direction: column;
    }

    .save-btn,
    .cancel-btn {
        width: 100%;
        text-align: center;
    }
}
</style>