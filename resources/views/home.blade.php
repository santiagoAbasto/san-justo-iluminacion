@extends('layouts.default')

@section('title', 'San Justo Iluminación | Fabricantes de iluminación')

@section('description', $metadatos->description ?? 'Diseñamos y fabricamos artefactos de iluminación para hogares, comercios y proyectos. Conocé nuestras líneas, catálogos y puntos de venta.')
@section('keywords', $metadatos->keywords ?? '')

@section('content')
    @if ($homeInfo)
    <x-banner-portada :homeInfo="$homeInfo" />
    <x-espacios :espaciosHome="$espaciosHome" :titulo="collect($titulos)->firstWhere('seccion', 'espacios')" />
    <x-seccion-uno :homeInfo="$homeInfo" />
    @if ($catalogos->count() > 0)
        <x-catalogos :catalogos="$catalogos" :titulo="collect($titulos)->firstWhere('seccion', 'catalogos')" />
    @endif
    <x-lineas-slider :lineas="$lineas" :titulo="collect($titulos)->firstWhere('seccion', 'lineas')" />
    <x-seccion-dos :homeInfo="$homeInfo" />
    <x-seccion-tres :homeInfo="$homeInfo" />
    <x-clientes-slider :clientes="$clientes" :titulo="collect($titulos)->firstWhere('seccion', 'marcas')" />

    <!-- Contenedor para el formulario modal -->
    <div id="dailyFormModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 p-3 sm:p-6">
        <div class="relative flex h-[493px] w-full max-w-[857px] flex-row overflow-y-auto bg-white shadow-2xl max-sm:h-[calc(100dvh-1.5rem)] max-sm:max-w-[380px] max-sm:flex-col">
    
            <!-- Botón cerrar -->
            <button id="closeFormModal"
                class="absolute top-3 right-3 z-10 flex h-11 w-11 items-center justify-center bg-white/90 text-2xl text-gray-600 shadow-sm transition-colors duration-200 hover:text-gray-800 sm:top-4 sm:right-5">
                &times;
            </button>
    
            <!-- Columna izquierda: título y formulario -->
            <div class="flex w-1/2 min-w-0 flex-col justify-center p-8 max-sm:w-full max-sm:p-5">
                <h2 class="mb-6 text-2xl font-bold text-gray-800 max-sm:mb-4 max-sm:pr-10 max-sm:text-xl">
                    {{ $homeInfo->custom_title_es }}
                </h2>
    
                <div class="w-full min-w-0 overflow-hidden" id="formContainer"></div>
            </div>
    
            <!-- Columna derecha: imagen -->
            <div class="w-1/2 max-sm:order-first max-sm:h-44 max-sm:w-full max-sm:shrink-0">
                <img src="{{ $homeInfo->custom_image_es }}" class="w-full h-full object-cover" alt="" loading="lazy"
                    decoding="async">
            </div>
        </div>
    </div>

    <style>
        #dailyFormModal .b24-form-wrapper,
        #dailyFormModal .b24-form,
        #dailyFormModal .b24-form-content,
        #dailyFormModal iframe {
            box-sizing: border-box !important;
            max-width: 100% !important;
            min-width: 0 !important;
            width: 100% !important;
        }
    </style>
    

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const completedFormKey = 'sanjusto_popup_form_completed_v1';
            let completedThisVisit = false;
            let bitrixLoaded = false;

            function getStoredValue(key) {
                try {
                    return localStorage.getItem(key);
                } catch (error) {
                    return null;
                }
            }

            function storeValue(key, value) {
                try {
                    localStorage.setItem(key, value);
                } catch (error) {
                    // Algunos navegadores bloquean localStorage. La cookie sirve
                    // de respaldo para recordar un envío confirmado por Bitrix.
                }
            }

            function hasCompletedForm() {
                if (completedThisVisit || getStoredValue(completedFormKey) === 'true') {
                    return true;
                }

                try {
                    return document.cookie.split(';').some(function(cookie) {
                        return cookie.trim() === completedFormKey + '=true';
                    });
                } catch (error) {
                    return false;
                }
            }

            function rememberCompletedForm() {
                completedThisVisit = true;
                storeValue(completedFormKey, 'true');

                try {
                    let cookie = completedFormKey + '=true; Max-Age=31536000; Path=/; SameSite=Lax';
                    const hostname = window.location.hostname;

                    // Compartir la confirmación entre el dominio con y sin www.
                    if (hostname === 'sanjustoiluminacion.com.ar' || hostname.endsWith('.sanjustoiluminacion.com.ar')) {
                        cookie += '; Domain=sanjustoiluminacion.com.ar';
                    }
                    if (window.location.protocol === 'https:') {
                        cookie += '; Secure';
                    }
                    document.cookie = cookie;
                } catch (error) {
                    // Si las cookies están bloqueadas, queda localStorage como respaldo.
                }
            }

            function shouldShowDailyForm() {
                if (hasCompletedForm()) {
                    return false;
                }

                const today = new Date().toDateString();
                return getStoredValue('sanjusto_last_visit') !== today;
            }

            function loadBitrixForm() {
                const formContainer = document.getElementById('formContainer');

                if (!formContainer || bitrixLoaded) {
                    return;
                }

                const formScript = document.createElement('script');
                formScript.setAttribute('data-b24-form', 'inline/8/akv4xt');
                formScript.setAttribute('data-skip-moving', 'true');
                formScript.async = true;
                formScript.src = 'https://cdn.bitrix24.es/b7493823/crm/form/loader_8.js?' + (Date.now() / 180000 | 0);
                formContainer.appendChild(formScript);
                bitrixLoaded = true;
            }

            function showModal() {
                // Otro tab puede haber completado el formulario durante el delay.
                if (hasCompletedForm()) {
                    hideModal();
                    return;
                }

                const modal = document.getElementById('dailyFormModal');
                if (!modal) {
                    return;
                }

                modal.style.display = 'flex';
                storeValue('sanjusto_last_visit', new Date().toDateString());
                loadBitrixForm();
            }

            function hideModal() {
                const modal = document.getElementById('dailyFormModal');
                if (!modal) {
                    return;
                }

                modal.style.display = 'none';
            }

            // Bitrix emite send:success después de confirmar el envío en el CRM.
            // No marcar por submit/click/cierre: podrían ser envíos fallidos.
            window.addEventListener('b24:form:send:success', function(event) {
                const form = event.detail && event.detail.object;
                const identification = form && form.identification;

                if (!identification || String(identification.id) !== '8' || identification.sec !== 'akv4xt') {
                    return;
                }

                rememberCompletedForm();
                hideModal();
            });

            function hideCompletedForm() {
                if (hasCompletedForm()) {
                    rememberCompletedForm();
                    hideModal();
                }
            }

            window.addEventListener('storage', function(event) {
                if (event.key === completedFormKey) {
                    hideCompletedForm();
                }
            });
            window.addEventListener('pageshow', hideCompletedForm);

            // Event listener para cerrar el modal
            const closeFormModal = document.getElementById('closeFormModal');
            const dailyFormModal = document.getElementById('dailyFormModal');

            if (closeFormModal) {
                closeFormModal.addEventListener('click', hideModal);
            }

            // Cerrar modal al hacer click fuera del contenido
            if (dailyFormModal) {
                dailyFormModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        hideModal();
                    }
                });
            }

            // Conservar las confirmaciones guardadas por versiones anteriores.
            if (hasCompletedForm()) {
                hideCompletedForm();
            } else if (shouldShowDailyForm()) {
                // Mostrar el modal después de un pequeño delay para que la página cargue
                setTimeout(showModal, 1000);
            }
        });
    </script>

    @else
        <main class="mx-auto flex min-h-[320px] max-w-[1200px] items-center justify-center px-4 text-center">
            <p class="text-gray-600">El contenido de la portada se publicará próximamente.</p>
        </main>
    @endif
@endsection
