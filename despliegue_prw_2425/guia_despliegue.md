# Guía de Despliegue - Proyecto Laravel

Esta guía te ayudará a desplegar la aplicación en un entorno de producción o pruebas.

## 1. Requisitos previos
- PHP >= 8.1
- Composer
- MySQL o MariaDB
- Node.js y npm
- Servidor web (Apache, Nginx, etc.)

## 2. Clonar el repositorio
```
git clone <URL_DEL_REPOSITORIO>
cd Proyecto2DAW
```

## 3. Instalar dependencias de PHP
```
composer install
```

## 4. Instalar dependencias de Node.js
```
npm install
```

## 5. Configurar variables de entorno
Copia el archivo `.env.example` a `.env` y edítalo con los datos de tu base de datos y configuración:
```
cp .env.example .env
```
Edita `.env` y configura:
- DB_DATABASE
- DB_USERNAME
- DB_PASSWORD
- Otros parámetros según tu entorno

## 6. Generar clave de aplicación
```
php artisan key:generate
```

## 7. Migrar la base de datos y sembrar datos
```
php artisan migrate --seed
```

## 8. Compilar los assets
```
npm run build
```

## 9. Configurar permisos
```
chmod -R 775 storage bootstrap/cache
```

## 10. Configurar el servidor web
- Apunta el DocumentRoot a la carpeta `public/` del proyecto.
- Configura el archivo `.htaccess` (Apache) o el bloque de servidor (Nginx) según corresponda.

## 11. Iniciar el servidor (modo desarrollo)
```
php artisan serve
```

## 12. Acceso a la aplicación
Abre tu navegador en `http://localhost:8000` o la URL configurada en tu servidor.

---

## 13. Tecnologías empleadas

### Backend
- Laravel (PHP 8.2+)
- JWT Auth (tymon/jwt-auth)
- L5 Swagger (documentación API)
- Inertia.js (integración backend-frontend)
- Laravel Sanctum (autenticación)
- MySQL o MariaDB (base de datos)

### Frontend
- Vue.js 3
- Inertia.js (Vue 3)
- Tailwind CSS
- Vite (empaquetador y servidor de desarrollo)
- Axios (peticiones HTTP)
- Vue Router

### Herramientas de desarrollo y testing
- PHPUnit (tests backend)
- Laravel Breeze (starter kit)
- Faker (datos de prueba)
- Mockery (mocks para tests)
- Laravel Pint (formateo de código)
- PostCSS y Autoprefixer

### Otros
- Node.js y npm (gestión de dependencias frontend)
- Composer (gestión de dependencias PHP)

---

## 14. Integración en la empresa

La aplicación puede integrarse fácilmente en la infraestructura de una empresa que utilice servidores Linux o Windows con soporte para PHP y Node.js. Se recomienda:
- Desplegar en un servidor dedicado o VPS para producción.
- Integrar con sistemas de autenticación existentes mediante JWT o Laravel Sanctum.
- Adaptar la base de datos a los sistemas internos (MySQL/MariaDB).
- Configurar backups automáticos de la base de datos y logs.
- Integrar con herramientas de CI/CD para despliegues automáticos (GitHub Actions, GitLab CI, etc.).
- Personalizar el frontend para la imagen corporativa.

La documentación Swagger facilita la integración con otros sistemas y equipos de desarrollo.

---

## 15. Coste posible de implantación

- **Licencias:** El stack es open source, por lo que no hay costes de licencias de software.
- **Infraestructura:**
  - VPS básico (2-4 GB RAM, 2 vCPU): 10-20 €/mes
  - Dominio: 10-15 €/año
  - Certificado SSL: gratuito (Let's Encrypt) o 30-50 €/año si es de pago
- **Mano de obra:**
  - Instalación y configuración inicial: 8-16 horas (desarrollador o sysadmin)
  - Mantenimiento mensual: 2-4 horas
- **Otros:**
  - Coste de copias de seguridad, monitorización y soporte según necesidades de la empresa

**Resumen:**
El coste principal es la infraestructura y la mano de obra. El software es gratuito. El coste mensual estimado para una pyme puede estar entre 15 y 50 € (infraestructura básica + dominio), más el coste de personal propio o externo para mantenimiento y soporte.

---

**Notas adicionales:**
- Para documentación de la API, accede a `/api-docs` si tienes Swagger configurado.
- Revisa los logs en `storage/logs/` ante cualquier error.
- Para producción, configura correctamente variables como `APP_ENV=production` y `APP_DEBUG=false`.

---

## 16. Conclusiones del proyecto

El desarrollo de este proyecto ha permitido crear una aplicación web moderna, robusta y escalable para la gestión de concesionarios y vehículos. Se han empleado tecnologías actuales y ampliamente adoptadas en la industria, lo que facilita el mantenimiento, la integración y la evolución futura del sistema.

Entre los principales logros y ventajas destacan:
- Arquitectura clara y modular, separando backend y frontend.
- Seguridad en la autenticación y gestión de usuarios mediante JWT y Laravel Sanctum.
- API documentada con Swagger, facilitando la integración con otros sistemas y equipos.
- Interfaz de usuario moderna y adaptable gracias a Vue.js y Tailwind CSS.
- Pruebas automatizadas que garantizan la calidad y fiabilidad del software.
- Facilidad de despliegue y adaptación a diferentes entornos empresariales.

El proyecto está preparado para crecer, integrarse con otros servicios y adaptarse a las necesidades de la empresa, asegurando una base tecnológica sólida y eficiente.

---

## 17. Bibliografía

- Documentación oficial de Laravel: https://laravel.com/docs
- Documentación oficial de Vue.js: https://vuejs.org/v2/guide/
- Documentación de Inertia.js: https://inertiajs.com/
- Tailwind CSS: https://tailwindcss.com/docs
- JWT Auth para Laravel: https://jwt-auth.readthedocs.io/en/develop/
- L5 Swagger para Laravel: https://github.com/DarkaOnLine/L5-Swagger
- PHPUnit: https://phpunit.de/
- Composer: https://getcomposer.org/doc/
- Node.js: https://nodejs.org/en/docs/
- Vite: https://vitejs.dev/guide/
- MySQL: https://dev.mysql.com/doc/
- GitHub Actions: https://docs.github.com/en/actions
- Let's Encrypt: https://letsencrypt.org/

Además de recursos y foros de la comunidad para resolución de dudas y buenas prácticas.

---

¡Despliegue completado!
