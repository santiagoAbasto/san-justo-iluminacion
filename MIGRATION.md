# Guía de migración

La raíz web canónica de esta aplicación es `public/`. `public_html/` se conserva temporalmente como copia del hosting anterior, pero no debe usarse como raíz web en el nuevo servidor.

## Requisitos

- PHP 8.2, 8.3 o 8.4 con las extensiones requeridas por Composer (incluidas GD, ZIP, XML, mbstring, PDO y Fileinfo).
- Composer 2.
- Node.js 20 LTS o 22 LTS y npm para compilar los recursos.
- MySQL compatible con Laravel 12.
- Apache con `mod_rewrite`, o reglas equivalentes en Nginx.

## Instalación

1. Copiar el proyecto sin `vendor/`, `node_modules/`, `composer.phar`, `public/build/`, `public/hot`, `public_html/`, logs ni cachés.
2. Crear el `.env` del nuevo host tomando `.env.example` como referencia. No reutilizar secretos sin rotarlos.
3. Ejecutar `composer install --no-dev --optimize-autoloader`.
4. Ejecutar `npm ci && npm run build` localmente o en el proceso de despliegue.
5. Configurar el document root del dominio para que apunte a la carpeta `public/`.
6. Dar permiso de escritura al usuario de PHP únicamente sobre `storage/` y `bootstrap/cache/`.
7. Ejecutar `php artisan migrate --force` después de respaldar la base de datos.
8. Ejecutar `php artisan storage:link` si el enlace `public/storage` no se despliega.
9. Ejecutar `php artisan optimize` y arrancar un worker para la cola, porque la aplicación usa `QUEUE_CONNECTION=database`.
10. Configurar el cron de Laravel: `* * * * * php /ruta/al/proyecto/artisan schedule:run`.

No se debe publicar la raíz completa del repositorio: solo `public/` debe quedar accesible desde Internet. El bloque de PHP generado por cPanel fue excluido deliberadamente; la versión de PHP se selecciona desde el panel o la configuración del nuevo host.
