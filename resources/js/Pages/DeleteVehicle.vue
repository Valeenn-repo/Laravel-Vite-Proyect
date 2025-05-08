<template>
    <div class="admin-vehicles-container">
      <h2>Administración de Vehículos</h2>
      
      <div class="actions-bar">
        <Link href="/vehiculos/create" class="add-btn">Añadir Vehículo</Link>
      </div>
      
      <div v-if="loading" class="loading">Cargando vehículos...</div>
      
      <div v-if="successMessage" class="success-message">{{ successMessage }}</div>
      <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>
      
      <table v-if="!loading && cars.length > 0" class="vehicle-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Imagen</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Año</th>
            <th>Precio</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="car in cars" :key="car.id">
            <td>{{ car.id }}</td>
            <td>
              <img v-if="car.image_url" :src="car.image_url" class="car-thumbnail" alt="Imagen del vehículo">
              <span v-else>Sin imagen</span>
            </td>
            <td>{{ car.make_name || 'Sin marca' }}</td>
            <td>{{ car.model_name || 'Sin modelo' }}</td>
            <td>{{ car.year }}</td>
            <td>{{ formatPrice(car.price) }}</td>
            <td>
              <span :class="'status-badge ' + car.status">
                {{ getStatusText(car.status) }}
              </span>
            </td>
            <td class="actions-column">
              <Link :href="'/cars/' + car.id" class="view-btn" title="Ver detalles">
                Ver
              </Link>
              <Link :href="'/vehiculos/edit/' + car.id" class="edit-btn" title="Editar">
                Editar
              </Link>
              <button @click="confirmDelete(car)" class="delete-btn" title="Eliminar">
                Eliminar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      
      <div v-if="!loading && cars.length === 0" class="no-data">
        No hay vehículos disponibles.
      </div>
      
      <!-- Modal de confirmación para eliminar -->
      <div v-if="showDeleteModal" class="delete-modal">
        <div class="modal-content">
          <h3>Confirmar eliminación</h3>
          <p>¿Estás seguro de que deseas eliminar el vehículo {{ selectedCar ? `${selectedCar.make_name} ${selectedCar.model_name} (${selectedCar.year})` : '' }}?</p>
          <p class="warning">Esta acción no se puede deshacer.</p>
          <div class="modal-actions">
            <button @click="deleteVehicle" class="confirm-btn">Sí, eliminar</button>
            <button @click="cancelDelete" class="cancel-btn">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { Link } from '@inertiajs/inertia-vue3'; // Import Link

  export default {
    name: 'AdminVehicles',
    components: { // Register Link component
      Link
    },
    data() {
      return {
        cars: [],
        loading: true,
        successMessage: '',
        errorMessage: '',
        showDeleteModal: false,
        selectedCar: null,
        deleteInProgress: false
      };
    },
    mounted() {
      this.loadCars();
    },
    methods: {
      async loadCars() {
        this.loading = true;
        this.errorMessage = '';
        
        try {
          const response = await axios.get('http://localhost:8000/api/cars');
          this.cars = response.data;
        } catch (error) {
          console.error('Error cargando vehículos:', error);
          this.errorMessage = 'Error al cargar la lista de vehículos.';
        } finally {
          this.loading = false;
        }
      },
      formatPrice(price) {
        return new Intl.NumberFormat('es-ES', {
          style: 'currency',
          currency: 'EUR'
        }).format(price);
      },
      getStatusText(status) {
        const statusMap = {
          'available': 'Disponible',
          'sold': 'Vendido',
          'reserved': 'Reservado'
        };
        
        return statusMap[status] || status;
      },
      confirmDelete(car) {
        this.selectedCar = car;
        this.showDeleteModal = true;
      },
      cancelDelete() {
        this.showDeleteModal = false;
        this.selectedCar = null;
      },
      async deleteVehicle() {
        if (this.deleteInProgress || !this.selectedCar) return;
        
        this.deleteInProgress = true;
        
        try {
          await axios.delete(`http://localhost:8000/api/cars/${this.selectedCar.id}`);
          
          // Eliminar el coche de la lista local
          this.cars = this.cars.filter(car => car.id !== this.selectedCar.id);
          
          this.successMessage = 'Vehículo eliminado correctamente.';
          setTimeout(() => {
            this.successMessage = '';
          }, 3000);
        } catch (error) {
          console.error('Error eliminando vehículo:', error);
          
          if (error.response && error.response.data && error.response.data.message) {
            this.errorMessage = `Error: ${error.response.data.message}`;
          } else {
            this.errorMessage = 'Error al eliminar el vehículo.';
          }
          
          setTimeout(() => {
            this.errorMessage = '';
          }, 5000);
        } finally {
          this.showDeleteModal = false;
          this.selectedCar = null;
          this.deleteInProgress = false;
        }
      }
    }
  };
  </script>
  
  <style scoped>
  .admin-vehicles-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
  }
  
  h2 {
    text-align: center;
    margin-bottom: 2rem;
  }
  
  .actions-bar {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 1.5rem;
  }
  
  .add-btn {
    background-color: #ffcc00;
    color: #232526;
    padding: 0.6rem 1.2rem;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 600;
    transition: background-color 0.2s;
  }
  
  .add-btn:hover {
    background-color: #ffbb00;
  }
  
  .vehicle-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
    background-color: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border-radius: 8px;
    overflow: hidden;
  }
  
  .vehicle-table th, 
  .vehicle-table td {
    padding: 0.8rem;
    text-align: left;
    border-bottom: 1px solid #eee;
  }
  
  .vehicle-table th {
    background-color: #f7f7f7;
    font-weight: 600;
  }
  
  .vehicle-table tr:last-child td {
    border-bottom: none;
  }
  
  .car-thumbnail {
    width: 60px;
    height: 40px;
    object-fit: cover;
    border-radius: 4px;
  }
  
  .actions-column {
    white-space: nowrap;
    text-align: center;
  }
  
  .view-btn, 
  .edit-btn, 
  .delete-btn {
    display: inline-block;
    padding: 0.3rem 0.6rem;
    border-radius: 4px;
    margin: 0 0.2rem;
    font-size: 0.8rem;
    cursor: pointer;
    text-decoration: none;
  }
  
  .view-btn {
    background-color: #e0f2ff;
    color: #0072c6;
    border: none;
  }
  
  .edit-btn {
    background-color: #fff2e0;
    color: #ff9900;
    border: none;
  }
  
  .delete-btn {
    background-color: #fee0e0;
    color: #d32f2f;
    border: none;
  }
  
  .view-btn:hover {
    background-color: #cce5ff;
  }
  
  .edit-btn:hover {
    background-color: #ffe8cc;
  }
  
  .delete-btn:hover {
    background-color: #ffcccc;
  }
  
  .status-badge {
    display: inline-block;
    padding: 0.2rem 0.6rem;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
  }
  
  .status-badge.available {
    background-color: #e6f7e6;
    color: #2e7d32;
  }
  
  .status-badge.sold {
    background-color: #e0e0e0;
    color: #616161;
  }
  
  .status-badge.reserved {
    background-color: #fff8e1;
    color: #ff8f00;
  }
  
  .loading, .no-data {
    text-align: center;
    padding: 2rem;
    color: #666;
    font-style: italic;
  }
  
  .success-message,
  .error-message {
    padding: 1rem;
    border-radius: 4px;
    margin-bottom: 1rem;
    text-align: center;
  }
  
  .success-message {
    background-color: #e6f7e6;
    color: #2e7d32;
    border: 1px solid #c8e6c9;
  }
  
  .error-message {
    background-color: #fee0e0;
    color: #d32f2f;
    border: 1px solid #ffcdd2;
  }
  
  /* Modal de eliminación */
  .delete-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
  }
  
  .modal-content {
    background-color: #fff;
    border-radius: 8px;
    padding: 2rem;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
  }
  
  .modal-content h3 {
    margin-top: 0;
    color: #d32f2f;
  }
  
  .warning {
    color: #d32f2f;
    font-weight: 500;
  }
  
  .modal-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 1.5rem;
    gap: 1rem;
  }
  
  .confirm-btn, 
  .cancel-btn {
    padding: 0.6rem 1.2rem;
    border-radius: 4px;
    font-weight: 500;
    cursor: pointer;
    border: none;
  }
  
  .confirm-btn {
    background-color: #d32f2f;
    color: white;
  }
  
  .cancel-btn {
    background-color: #e0e0e0;
    color: #424242;
  }
  
  .confirm-btn:hover {
    background-color: #b71c1c;
  }
  
  .cancel-btn:hover {
    background-color: #bdbdbd;
  }
  
  @media (max-width: 768px) {
    .vehicle-table {
      font-size: 0.9rem;
    }
    
    .vehicle-table th,
    .vehicle-table td {
      padding: 0.6rem;
    }
    
    .car-thumbnail {
      width: 50px;
      height: 35px;
    }
    
    .view-btn, 
    .edit-btn, 
    .delete-btn {
      padding: 0.2rem 0.4rem;
      font-size: 0.7rem;
    }
  }
  </style>