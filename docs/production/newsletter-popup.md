# Newsletter: recordar un envío confirmado

## Corrección

La versión anterior se suscribía a `complete`, un evento que el formulario de
Bitrix no emite. Ahora se escucha `b24:form:send:success` y se verifica la
identidad del newsletter (`8/akv4xt`). Se registra la confirmación únicamente
después de un envío exitoso, nunca por un clic, cierre o intento fallido.

El estado existente `sanjusto_popup_form_completed_v1` se conserva. Se guarda
también una cookie de respaldo, compartida entre el dominio con y sin `www`.
No se almacenan nombres, correos ni teléfonos en esta marca. Si ya existe una
confirmación, no se carga el formulario externo ni se abre el modal.

El nombre del evento y el objeto de identificación se verificaron en el
[código público de Bitrix](https://sanjustoiluminacion.bitrix24.es/bitrix/js/crm/site/form/dist/app.bundle.js).

## Publicación

El único archivo de aplicación modificado es `resources/views/home.blade.php`.
No requiere recompilar los assets, cambiar la base de datos ni tocar `.env`.

Después de publicar el commit en `main`, ejecutar en el servidor:

```bash
cd /home/zjuhyozy/San-Justo-Iluminacion
git status --short
git pull --ff-only origin main
php artisan view:clear
php artisan view:cache
```

Si Git informa cambios locales que impiden el pull, detenerse y revisarlos;
no descartarlos. Como alternativa, respaldar y reemplazar únicamente
`resources/views/home.blade.php` dentro del proyecto mediante cPanel y ejecutar
los dos comandos de vistas. No se sube a `public_html`.

## Verificación

Pruebas locales, sin enviar datos al CRM:

```bash
php artisan test --compact
node --test tests/JavaScript/newsletter-popup.test.mjs
```

Las pruebas cubren la confirmación exitosa, visitas en días posteriores,
errores, otros formularios, cierres, almacenamiento bloqueado, regreso con Atrás
y confirmación en otra pestaña. Los eventos exitosos se simulan: no crean leads.

En producción, después de que una persona haga un envío real exitoso, verificar
que el popup se cierre y no reaparezca al recargar o volver al inicio. No borrar
los datos del sitio para esta comprobación: eso elimina la identificación.

## Alcance y envíos anteriores

La identificación es por navegador, no por persona en todos sus dispositivos.
Si se borran cookies y almacenamiento, se usa otro navegador/dispositivo o se
bloquean ambos mecanismos, no se puede mantener esa identificación. La cookie
se renueva durante las visitas y dura hasta un año; localStorage no tiene un
vencimiento establecido por la aplicación.

Se respetan todas las confirmaciones anteriores que sí se hayan guardado.
Los envíos históricos que no dejaron una marca por el error anterior no pueden
reconocerse automáticamente con este arreglo. Los campos autocompletados de
Bitrix no son prueba de un envío exitoso y no se usan para ocultar el popup.
