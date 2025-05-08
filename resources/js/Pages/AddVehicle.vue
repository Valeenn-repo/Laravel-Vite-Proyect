<template>
    <div class="add-vehicle-container">
      <h2>Añadir Vehículo</h2>
      <form @submit.prevent="submitForm">
        <div class="form-group">
          <label>Marca:</label>
          <select v-model="selectedMarca" @change="filtrarModelos" required>
            <option value="" disabled selected>Selecciona una marca</option>
            <option v-for="marca in marcas" :key="marca.id" :value="marca.id">{{ marca.name }}</option>
          </select>
        </div>
        <div class="form-group">
          <label>Modelo:</label>
          <input v-model="vehicle.modelo" required placeholder="Introduce el modelo" />
        </div>
        <div class="form-group">
          <label>Concesionario:</label>
          <select v-model="vehicle.dealership_id" required>
            <option value="" disabled selected>Selecciona un concesionario</option>
            <option v-for="dealership in dealerships" :key="dealership.id" :value="dealership.id">{{ dealership.name }}</option>
          </select>
        </div>
        <div class="form-group">
          <label>VIN:</label>
          <input v-model="vehicle.vin" maxlength="17" minlength="17" required placeholder="Introduce el VIN (17 caracteres)" />
        </div>
        <div class="form-group">
          <label>Año:</label>
          <input v-model="vehicle.year" type="number" min="1900" max="2100" required placeholder="Introduce el año" />
        </div>
        <div class="form-group">
          <label>Precio:</label>
          <input v-model="vehicle.price" type="number" min="0" required placeholder="Introduce el precio" />
        </div>
        <div class="form-group">
          <label>Kilómetros:</label>
          <input v-model="vehicle.mileage" type="number" min="0" placeholder="Introduce el kilometraje" />
        </div>
        <div class="form-group">
          <label>Transmisión:</label>
          <select v-model="vehicle.transmission_type">
            <option value="">Selecciona</option>
            <option value="manual">Manual</option>
            <option value="automatic">Automático</option>
          </select>
        </div>
        <div class="form-group">
          <label>Combustible:</label>
          <select v-model="vehicle.fuel_type">
            <option value="">Selecciona</option>
            <option value="Gasolina">Gasolina</option>
            <option value="Diésel">Diésel</option>
            <option value="Híbrido">Híbrido</option>
            <option value="Eléctrico">Eléctrico</option>
          </select>
        </div>
        <div class="form-group">
          <label>Estado:</label>
          <select v-model="vehicle.status">
            <option value="available">Disponible</option>
            <option value="sold">Vendido</option>
            <option value="reserved">Reservado</option>
          </select>
        </div>
        <div class="form-group">
          <label>Color:</label>
          <input v-model="vehicle.color" placeholder="Introduce el color" />
        </div>
        <div class="form-group">
          <label>Imagen (URL):</label>
          <input v-model="vehicle.imagen" placeholder="Introduce la URL de la imagen" />
        </div>
        <button type="submit">Guardar Vehículo</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: "AddVehicle",
    data() {
      return {
        marcas: [],
        modelos: [],
        modelosFiltrados: [],
        dealerships: [],
        selectedMarca: "",
        vehicle: {
          modelo: "",
          dealership_id: "",
          vin: "",
          year: "",
          price: "",
          mileage: "",
          transmission_type: "",
          fuel_type: "",
          color: "",
          imagen: "",
          status: "available"
        },
        successMessage: "",
        errorMessage: "",
        showDebug: true,
        debugData: null
      };
    },
    mounted() {
      this.cargarMarcasYModelos();
      this.cargarDealerships();
    },
    methods: {
      async cargarMarcasYModelos() {
        try {
          const response = await axios.get("http://localhost:8000/api/car-data");
          this.marcas = response.data.makes || response.data;
          this.modelos = [];
          
          // Handle both possible response formats
          if (response.data.makes) {
            // New format
            response.data.makes.forEach(marca => {
              marca.models.forEach(modelo => {
                this.modelos.push({ ...modelo, make_id: marca.id });
              });
            });
          } else {
            // Old format
            response.data.forEach(marca => {
              marca.models.forEach(modelo => {
                this.modelos.push({ ...modelo, make_id: marca.id });
              });
            });
          }
        } catch (error) {
          console.error("Error cargando marcas y modelos:", error);
          this.errorMessage = "Error al cargar marcas y modelos.";
        }
      },
      filtrarModelos() {
        this.vehicle.model_id = "";
        this.modelosFiltrados = this.modelos.filter(m => m.make_id == this.selectedMarca);
      },
      async cargarDealerships() {
        try {
          const response = await axios.get("http://localhost:8000/api/dealerships");
          this.dealerships = response.data;
        } catch (error) {
          console.error("Error cargando concesionarios:", error);
          this.errorMessage = "Error al cargar concesionarios.";
        }
      },
      async submitForm() {
        this.successMessage = "";
        this.errorMessage = "";
        
        try {
          // Validar campos antes de enviar
          if (!this.selectedMarca) {
            this.errorMessage = "Por favor, selecciona una marca.";
            return;
          }
          
          if (!this.vehicle.modelo) {
            this.errorMessage = "Por favor, introduce un modelo.";
            return;
          }
          
          if (!this.vehicle.dealership_id) {
            this.errorMessage = "Por favor, selecciona un concesionario.";
            return;
          }
          
          if (!this.vehicle.vin || this.vehicle.vin.length !== 17) {
            this.errorMessage = "El VIN debe tener exactamente 17 caracteres.";
            return;
          }
          
          // Asegurarse de que los valores numéricos sean números antes de enviar
          const carData = {
            ...this.vehicle,
            make_id: parseInt(this.selectedMarca) || null,
            dealership_id: parseInt(this.vehicle.dealership_id) || null,
            year: parseInt(this.vehicle.year) || null,
            price: parseFloat(this.vehicle.price) || null,
            mileage: this.vehicle.mileage ? parseInt(this.vehicle.mileage) : null
          };
          
          // Para debug
          this.debugData = JSON.stringify(carData, null, 2);
          console.log("Enviando datos:", carData);
          
          try {
            const response = await axios.post("http://localhost:8000/api/cars", carData, {
              headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
              }
            });
            console.log("Respuesta:", response.data);
            
            this.successMessage = "Vehículo añadido correctamente.";
            this.vehicle = {
              modelo: "",
              dealership_id: "",
              vin: "",
              year: "",
              price: "",
              mileage: "",
              transmission_type: "",
              fuel_type: "",
              color: "",
              imagen: "",
              status: "available"
            };
            this.selectedMarca = "";
            this.modelosFiltrados = [];
          } catch (error) {
            console.error("Error añadiendo vehículo:", error);
            
            // Mostrar toda la respuesta para depuración
            if (error.response) {
              console.log("Respuesta de error:", error.response);
              console.log("Datos de error:", error.response.data);
              console.log("Estado:", error.response.status);
              console.log("Cabeceras:", error.response.headers);
              
              if (error.response.data && typeof error.response.data === 'object') {
                this.errorMessage = `Error del servidor: ${JSON.stringify(error.response.data)}`;
              } else if (error.response.data) {
                this.errorMessage = `Error del servidor: ${error.response.data}`;
              } else {
                this.errorMessage = `Error ${error.response.status}: ${error.response.statusText}`;
              }
            } else if (error.request) {
              this.errorMessage = "Error de conexión: No se recibió respuesta del servidor.";
            } else {
              this.errorMessage = `Error: ${error.message}`;
            }
          }
        } catch (generalError) {
          console.error("Error general:", generalError);
          this.errorMessage = `Error general: ${generalError.message}`;
        }
      }
    }
  };
  </script>
  
  <style scoped>
  .add-vehicle-container {
    max-width: 500px;
    margin: 2rem auto;
    background: #fff;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  }
  h2 {
    text-align: center;
    margin-bottom: 1.5rem;
  }
  .form-group {
    margin-bottom: 1rem;
  }
  label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.3rem;
  }
  input, select, textarea {
    width: 100%;
    padding: 0.5rem;
    border-radius: 4px;
    border: 1px solid #ccc;
  }
  button[type="submit"] {
    width: 100%;
    background: #ffcc00;
    color: #232526;
    border: none;
    border-radius: 5px;
    font-weight: 600;
    padding: 0.7rem;
    cursor: pointer;
    margin-top: 1rem;
    transition: background 0.2s;
  }
  button[type="submit"]:hover {
    background: #ffbb00;
  }
  .success-message {
    color: green;
    margin-top: 1rem;
    text-align: center;
  }
  .error-message {
    color: red;
    margin-top: 1rem;
    text-align: center;
  }
  .debug-section {
    margin-top: 20px;
    padding: 10px;
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 4px;
  }
  .debug-section pre {
    white-space: pre-wrap;
    word-wrap: break-word;
    max-height: 200px;
    overflow-y: auto;
  }
  </style>