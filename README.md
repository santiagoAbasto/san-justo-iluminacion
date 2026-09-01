# San Justo Iluminación

Sitio web institucional y plataforma de gestión comercial de **San Justo Iluminación**. Reúne el catálogo de productos, novedades, recursos, puntos de venta, formularios de contacto y un área privada para clientes y administración.

## Funcionalidades

- Catálogo con categorías, líneas, ambientes, usos, marcas, modelos y colores.
- Buscador y páginas de detalle de productos.
- Gestión de banners, novedades, recursos, certificados y contenido institucional.
- Localizador de puntos de venta.
- Área privada con listas de precios, pedidos, márgenes e información comercial.
- Panel de administración y procesos de importación desde Excel/CSV.
- Formularios de contacto, solicitudes y envío de correos.
- Sitio localizado en español e inglés.

## Tecnologías

- PHP 8.2 y Laravel 12
- React 18, TypeScript e Inertia.js 2
- Tailwind CSS 4 y Vite 6
- Pest / PHPUnit
- MySQL o una base de datos compatible con Laravel

## Requisitos

- PHP 8.2 o superior con las extensiones requeridas por Laravel
- Composer 2
- Node.js 20 o superior y npm
- MySQL 8 o equivalente

## Instalación local

```bash
git clone https://github.com/santiagoAbasto/san-justo-iluminacion.git
cd san-justo-iluminacion
composer install
npm ci
cp .env.example .env
php artisan key:generate
```

Configura la conexión a la base de datos, el correo y reCAPTCHA en `.env`. Después ejecuta:

```bash
php artisan migrate
php artisan storage:link
composer run dev
```

La aplicación quedará disponible, por defecto, en `http://localhost:8000`.

## Comandos útiles

```bash
# Desarrollo (servidor, cola y Vite)
composer run dev

# Compilación de producción
npm run build

# Pruebas
php artisan test

# Verificación de tipos
npm run types

# Formato del frontend
npm run format:check
```

## Despliegue

En producción, configura las variables de entorno fuera del repositorio y ejecuta:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan optimize
```

El servidor web debe apuntar al directorio `public/`. Asegúrate también de configurar el worker de colas y los permisos de escritura de `storage/` y `bootstrap/cache/`.

## Seguridad

No publiques el archivo `.env`, respaldos de base de datos ni archivos cargados por usuarios. Si detectas una vulnerabilidad, comunícala de forma privada a los responsables del proyecto en lugar de abrir un issue público.

## Versión

La primera versión estable del proyecto es **v1.0.0**.

## Licencia

Este proyecto es de uso propietario. El código y los recursos de marca pertenecen a sus respectivos titulares; no se concede permiso de uso, copia, modificación o distribución sin autorización expresa.
