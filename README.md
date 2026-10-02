# San Justo Iluminación

Aplicación web para publicar y administrar el catálogo y los contenidos comerciales de San Justo Iluminación, con zona privada de pedidos y panel administrativo.

## Descripción

El proyecto reúne un sitio público de iluminación con catálogo, contenidos institucionales, novedades, recursos, calidad y puntos de venta. Permite consultar productos mediante filtros y centraliza formularios de contacto y postulaciones laborales.

También incorpora autenticación de usuarios, carrito, pedidos, listas de precios y márgenes en una zona privada, además de un área administrativa separada para mantener contenidos y datos comerciales. El historial reciente muestra ajustes de navegación e interfaz del catálogo. No hay evidencia versionada de hosting o CI/CD.

## Funcionalidades principales

- Catálogo público paginado con filtros por espacio, uso, línea, ambiente y código.
- Detalle de producto, búsqueda y endpoints JSON para filtros dependientes.
- Gestión administrativa de productos, imágenes, colores, líneas, espacios, usos, ambientes, marcas, novedades, sliders y contenido institucional.
- Localizador de puntos de venta y consulta de localidades.
- Formularios de contacto y postulación laboral con envío de correo.
- Autenticación, recuperación de contraseña, perfil y actualización de contraseña.
- Zona privada con carrito, pedidos, recompra, listas de precios, información de pago y márgenes.
- Autenticación administrativa independiente.
- Importación de productos, clientes, vendedores, ofertas y precios desde Excel; varias importaciones se procesan mediante colas.
- Generación de sitemap XML y campos de contenido en español e inglés.

## Arquitectura

Es un monolito web Laravel. El backend concentra rutas, controladores, validación, modelos Eloquent, correo, colas y acceso a datos. El frontend combina vistas Blade del sitio público con páginas React mediante Inertia para áreas de aplicación y administración. Vite construye los recursos frontend y Ziggy integra las rutas Laravel en React.

```text
Visitante / usuario / administrador
              |
      Laravel: rutas y middleware
              |
Controladores + validación + Eloquent
       |              |              |
Blade / Inertia   colas de importación   correo / reCAPTCHA
       |              |              |
 React + Vite     tabla jobs          servicios configurados
              |
        Base de datos relacional
```

No se identificaron microservicios, multi-tenancy, API versionada independiente ni componentes de IA implementados.

## Stack tecnológico

### Backend

- PHP 8.2, Laravel 12 e Inertia Laravel 2.
- Eloquent ORM.
- PhpSpreadsheet 5.9 para importar Excel.
- Spatie Laravel Sitemap para el sitemap.

### Frontend

- React 18, TypeScript e Inertia React 2.
- Vite 6 y Tailwind CSS 4.
- Ziggy para rutas Laravel en el cliente.

### Base de datos

- La configuración admite SQLite, MySQL/MariaDB, PostgreSQL y SQL Server.
- El ejemplo de entorno usa MySQL; las pruebas se declaran para SQLite en memoria.
- Sesiones, caché y colas pueden persistirse en base de datos.

### Testing

- Pest 3 sobre PHPUnit, con pruebas unitarias y feature.

### DevOps / Infraestructura

- Scripts Composer para servidor Laravel, worker de colas y Vite en paralelo.
- Build de frontend con Vite.
- No se encontraron Dockerfile, Docker Compose, GitHub Actions, GitLab CI ni Jenkinsfile versionados.

### Integraciones

- Google reCAPTCHA en un flujo de formulario.
- Correo mediante Laravel.
- Laravel Filesystem para archivos; las cargas públicas usan el disco `public`.

## Modelo de datos

Eloquent representa entidades de catálogo, operación comercial y contenido editorial. `Producto` tiene imágenes y relaciones muchos-a-muchos con ambientes y colores; además referencia espacio, uso y línea. Los usos pertenecen a un espacio y las líneas se relacionan con ambientes. Varias migraciones definen claves foráneas y borrado en cascada para imágenes y tablas pivote.

Los usuarios se relacionan con vendedores, sucursales, ofertas y listas de precios; los pedidos pertenecen a un usuario y agrupan productos de pedido. También existen entidades para puntos de venta, recursos, calidad, novedades, banners, contactos y metadatos. Las altas, actualizaciones y bajas de productos revisadas usan transacciones para coordinar registros, relaciones y archivos. No se observó auditoría de cambios ni aislamiento por tenant.

## Seguridad

- Usuarios y administradores usan guards de sesión distintos: `web` y `admin`. Las rutas administrativas están bajo `auth:admin`; dashboard y zona privada requieren autenticación.
- Las contraseñas usan el cast `hashed`; `User` oculta contraseña y token de recuerdo al serializarse.
- Login, registro, recuperación de contraseña, perfil y contraseña usan validación de Laravel. Perfil y eliminación de cuenta verifican la contraseña actual.
- Las operaciones de productos validan archivos, imágenes y referencias existentes antes de persistirlos.
- Las consultas revisadas usan Eloquent y el query builder de Laravel, con parámetros enlazados por el framework.
- Las rutas de verificación de correo aplican `signed` y `throttle:6,1`; el grupo web de Laravel aporta protección CSRF para formularios web.
- Las claves de base de datos, SMTP y reCAPTCHA se resuelven por entorno; no se incluyen valores ni destinos operativos.

No hay evidencia suficiente para afirmar RBAC granular, CSP, rate limiting global, escaneo de dependencias, auditoría o políticas de carga adicionales.

## APIs e integraciones

Las rutas web incluyen respuestas JSON para usos por espacio, ambientes por línea, búsqueda y datos de puntos de venta/localidades. No hay especificación OpenAPI ni módulo `routes/api.php` versionado. Los formularios usan correo de Laravel y existe una integración reCAPTCHA. La configuración se resuelve por entorno y no se publica.

## Testing y calidad

El repositorio contiene pruebas Pest unitarias y feature de autenticación, recuperación y confirmación de contraseña, verificación de correo, dashboard y perfil.

Se ejecutó `php artisan test` sobre SQLite en memoria, como establece `phpunit.xml`. Resultado de la revisión del 2 de octubre de 2026: **41 pruebas exitosas y 144 aserciones**, incluyendo filtros públicos, miniaturas, acceso de cuentas existentes y preservación de usuarios al revertir la migración de respaldo.

También se verificaron `npm run types`, `npm run build` y la sintaxis PHP de las vistas Blade compiladas. Las páginas Inertia antiguas mantienen una configuración de tipos permisiva; esta comprobación no sustituye la validación funcional. `npm run lint` y `npm run format:check` están disponibles, pero no forman parte de esta verificación.

## DevOps y despliegue

`composer run dev` inicia servidor Laravel, listener de colas y Vite. `composer run dev:ssr` construye e inicia SSR de Inertia junto con esos procesos. `npm run build` genera el build frontend. No hay CI/CD, Docker, configuración cloud ni orquestación versionados. Las importaciones asíncronas requieren un worker compatible con la conexión de cola.

Los activos de `public/build` no se versionan: los cambios React requieren compilar y publicar el directorio completo, incluido `manifest.json`. Si el hosting no dispone de Node.js/npm, se construyen localmente y se suben al directorio `build` de la raíz pública del servidor. Las instalaciones existentes deben respaldar su base de datos antes de aplicar migraciones; la migración de respaldo de `users` no modifica una tabla existente y conserva los usuarios al revertirse.

## Instalación local

Requisitos comprobables: PHP 8.2, Composer, Node.js/npm y una base de datos compatible. El ejemplo de entorno está preparado para MySQL y existe `package-lock.json`.

1. Instalar dependencias:

   ```bash
   composer install
   npm ci
   ```

2. Crear el entorno local y configurar valores propios para aplicación, base de datos, correo y reCAPTCHA:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Aplicar las migraciones disponibles y crear el enlace público:

   ```bash
   php artisan migrate
   php artisan storage:link
   ```

4. Iniciar desarrollo:

   ```bash
   composer run dev
   ```

> Nota: la migración original tiene comentada la creación de `users`; la migración `2026_09_14_000000_create_users_table_when_missing.php` crea un esquema compatible solo si falta la tabla. Su reversión es intencionalmente no destructiva. Las cuentas conservan el flujo de autorización administrativa, sin añadir verificación obligatoria de correo.

## Estructura del proyecto

```text
app/
├── Console/Commands/       # comandos de importación
├── Http/Controllers/       # sitio, administración, auth y zona privada
├── Http/Middleware/        # locale, Inertia y accesos
├── Jobs/                   # importaciones en cola
├── Mail/                   # correos transaccionales
└── Models/                 # entidades Eloquent
database/
├── migrations/
├── factories/
└── seeders/
resources/
├── css/
├── js/                     # páginas React/Inertia y componentes
└── views/                  # vistas Blade
routes/                     # rutas públicas, auth y administración
config/                     # servicios, datos, colas y filesystem
tests/                      # pruebas Pest
public/                     # punto de entrada y activos públicos
```

## Decisiones técnicas destacables

- Guards y providers Eloquent diferentes para usuarios y administración.
- Inertia para páginas de aplicación y administración, con Laravel como backend.
- Trabajos `ShouldQueue` para importaciones Excel.
- Transacciones para crear, modificar y eliminar productos y relaciones.
- Laravel Filesystem para medios y documentos.

## Desafíos técnicos resueltos

- Problema: mantener coherentes producto, imágenes, colores y ambientes.
  Solución: validación y transacciones en operaciones de producto.

- Problema: procesar datos comerciales sin bloquear solicitudes web.
  Solución: jobs de importación para productos, clientes, vendedores, ofertas y precios.

- Problema: ofrecer filtros dependientes del catálogo.
  Solución: endpoints JSON para usos por espacio y ambientes por línea.

## Estado del proyecto

**Otro: aplicación comercial web en desarrollo activo.** El repositorio contiene sitio público, administración y operación comercial; el historial reciente incluye ajustes del catálogo. No hay evidencia suficiente para clasificarlo como producto en producción, SaaS, proyecto académico o proyecto de cliente.

## Mi contribución

La autoría individual no puede determinarse de forma fiable solo con este checkout. Para un portfolio, el código evidencia una aplicación Laravel/React con catálogo filtrable, administración, zona privada de pedidos, importaciones Excel en cola y flujos de autenticación y correo. Esta sección debe ajustarse a la participación personal comprobable antes de usarla en una postulación.

### Portfolio Summary

Nombre:
San Justo Iluminación

Tipo:
Aplicación comercial web en desarrollo activo

Rol:
Por confirmar según la participación personal; el repositorio no permite atribuir un rol individual.

Descripción corta:
Plataforma Laravel para catálogo de iluminación, contenidos comerciales, administración e interacción comercial privada.

Stack principal:
PHP 8.2, Laravel 12, React 18, TypeScript, Inertia, Vite, Tailwind CSS, Eloquent y Pest.

Arquitectura:
Monolito Laravel con Blade e Inertia/React; modelos Eloquent, colas de base de datos y Laravel Filesystem.

Seguridad:
Guards de sesión separados, middleware de acceso, validación Laravel, hashing de contraseñas y secretos por entorno.

Testing:
Pest con suites unitarias y feature. Verificación local: 41 pruebas exitosas y 144 aserciones.

DevOps:
Scripts Composer para servidor, worker y Vite; build Vite. Sin CI/CD ni Docker versionados.

IA:
No hay IA implementada identificable en el repositorio.

5 habilidades clave:
1.
Desarrollo full stack con Laravel y React.
2.
Modelado relacional con Eloquent y migraciones.
3.
Integración de interfaces SPA con Inertia.
4.
Procesamiento asíncrono con colas e importaciones Excel.
5.
Autenticación basada en sesión y validación de solicitudes.

3 logros o aportes verificables:

- Implementación de filtros de catálogo y endpoints JSON dependientes de espacio y línea.
- Operaciones transaccionales para administrar productos, medios y relaciones.
- Jobs de cola para importar productos y datos comerciales desde Excel.

Nivel de madurez:
Aplicación comercial en desarrollo activo, con suite de pruebas ejecutable sobre SQLite en memoria.

Elementos que no deben publicarse:

- Credenciales, claves de aplicación, datos de base de datos, SMTP y reCAPTCHA.
- Archivos de entorno y contenido de cargas de usuarios o datos comerciales.
- URLs operativas de servicios externos no necesarias para comprender el proyecto.

## Evidencia técnica

| Afirmación | Evidencia |
| --- | --- |
| Laravel 12 y PHP 8.2 | `composer.json` |
| React, TypeScript, Vite y Tailwind | `package.json`, `vite.config.ts`, `resources/js/app.tsx` |
| Catálogo, zona privada y administración | `routes/web.php`, `routes/auth.php`, `routes/admin_auth.php` |
| Guards separados | `config/auth.php`, `app/Models/Admin.php` |
| Relaciones y transacciones de productos | `app/Models/Producto.php`, `app/Http/Controllers/ProductoController.php`, `database/migrations/` |
| Importaciones asíncronas | `app/Http/Controllers/ImportController.php`, `app/Jobs/` |
| Pruebas Pest y SQLite en memoria | `tests/`, `phpunit.xml` |
| Ausencia de Docker y CI/CD | inventario de archivos versionados |
