  import { createApp, h } from 'vue';
  import { createInertiaApp, Link } from '@inertiajs/inertia-vue3';
  import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
  import { Inertia } from '@inertiajs/inertia';
  import axios from 'axios';

  // In your main.js
  console.log("Token on app load:", localStorage.getItem('jwt_token'));

  // Configurar interceptor para Inertia
  Inertia.on('before', (event) => {
      const token = localStorage.getItem('jwt_token');
      console.log("Inertia Interceptor - Token:", token);
      if (token) {
          event.detail.visit.headers['Authorization'] = `Bearer ${token}`;
          console.log("Inertia Interceptor - Headers:", event.detail.visit.headers);
      }
  });

  // Set a default Authorization header for all subsequent axios requests
  axios.interceptors.request.use(config => {
      const token = localStorage.getItem('jwt_token');
      console.log("Axios Interceptor - Token:", token);
      if (token) {
        config.headers.Authorization = `Bearer ${token}`;
        console.log("Axios Interceptor - Axios Config Headers:", config.headers);
      }
      return config;
    }, error => {
      return Promise.reject(error);
    });
    
  createInertiaApp({
      resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
      setup({ el, App, props, plugin }) {
          createApp({ render: () => h(App, props) })
              .component('Link', Link)
              .use(plugin)
              .mount(el);
      },
  });