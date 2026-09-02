{{-- resources/views/components/footer.blade.php --}}
<div class="flex h-fit w-full flex-col bg-[#F2EEED]">
    <div
        class="mx-auto flex h-full w-full max-w-[1224px] flex-col items-center justify-between gap-12 px-4 py-12 sm:gap-16 sm:px-6 sm:py-16 lg:flex-row lg:items-start lg:gap-20 lg:px-0 lg:py-18">
        {{-- Logo y redes sociales --}}
        <div class="flex h-full flex-col items-center gap-6">
            <a href="/">
                <img src="{{ $logos->logo_secundario ?? '' }}" alt="Logo secundario"
                    class="max-w-[124px] max-h-[84px] sm:max-w-full" />
            </a>

            <div class="flex flex-row items-center justify-center gap-6 ">

                @if(!empty($contacto->ig))
                    <a target="_blank" rel="noopener noreferrer" href="{{ $contacto->ig }}" aria-label="Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21" fill="none">
                            <path
                                d="M11.0286 0.340332C12.1536 0.343332 12.7246 0.349332 13.2176 0.363332L13.4116 0.370332C13.6356 0.378332 13.8566 0.388332 14.1236 0.400332C15.1876 0.450332 15.9136 0.618332 16.5506 0.865332C17.2106 1.11933 17.7666 1.46333 18.3226 2.01833C18.8313 2.51806 19.2248 3.1228 19.4756 3.79033C19.7226 4.42733 19.8906 5.15333 19.9406 6.21833C19.9526 6.48433 19.9626 6.70533 19.9706 6.93033L19.9766 7.12433C19.9916 7.61633 19.9976 8.18733 19.9996 9.31233L20.0006 10.0583V11.3683C20.003 12.0977 19.9953 12.8271 19.9776 13.5563L19.9716 13.7503C19.9636 13.9753 19.9536 14.1963 19.9416 14.4623C19.8916 15.5273 19.7216 16.2523 19.4756 16.8903C19.2248 17.5579 18.8313 18.1626 18.3226 18.6623C17.8228 19.171 17.2181 19.5645 16.5506 19.8153C15.9136 20.0623 15.1876 20.2303 14.1236 20.2803L13.4116 20.3103L13.2176 20.3163C12.7246 20.3303 12.1536 20.3373 11.0286 20.3393L10.2826 20.3403H8.97357C8.24383 20.3429 7.51409 20.3352 6.78457 20.3173L6.59057 20.3113C6.35318 20.3023 6.11584 20.292 5.87857 20.2803C4.81457 20.2303 4.08857 20.0623 3.45057 19.8153C2.7834 19.5644 2.17901 19.1709 1.67957 18.6623C1.17051 18.1627 0.776678 17.558 0.525569 16.8903C0.278569 16.2533 0.110569 15.5273 0.0605687 14.4623L0.0305688 13.7503L0.0255689 13.5563C0.00713493 12.8271 -0.00119929 12.0977 0.000568797 11.3683V9.31233C-0.0021991 8.58293 0.00513501 7.85353 0.0225689 7.12433L0.0295688 6.93033C0.0375688 6.70533 0.0475688 6.48433 0.0595688 6.21833C0.109569 5.15333 0.277569 4.42833 0.524569 3.79033C0.776263 3.12253 1.17079 2.51777 1.68057 2.01833C2.17972 1.50988 2.78376 1.11641 3.45057 0.865332C4.08857 0.618332 4.81357 0.450332 5.87857 0.400332C6.14457 0.388332 6.36657 0.378332 6.59057 0.370332L6.78457 0.364332C7.51376 0.346565 8.24316 0.338897 8.97257 0.341332L11.0286 0.340332ZM10.0006 5.34033C8.67449 5.34033 7.40272 5.86712 6.46503 6.8048C5.52735 7.74248 5.00057 9.01425 5.00057 10.3403C5.00057 11.6664 5.52735 12.9382 6.46503 13.8759C7.40272 14.8135 8.67449 15.3403 10.0006 15.3403C11.3267 15.3403 12.5984 14.8135 13.5361 13.8759C14.4738 12.9382 15.0006 11.6664 15.0006 10.3403C15.0006 9.01425 14.4738 7.74248 13.5361 6.8048C12.5984 5.86712 11.3267 5.34033 10.0006 5.34033ZM10.0006 7.34033C10.3945 7.34027 10.7847 7.4178 11.1487 7.5685C11.5127 7.71921 11.8434 7.94013 12.122 8.21866C12.4007 8.49719 12.6217 8.82787 12.7725 9.19182C12.9233 9.55577 13.001 9.94587 13.0011 10.3398C13.0011 10.7338 12.9236 11.1239 12.7729 11.4879C12.6222 11.8519 12.4013 12.1827 12.1227 12.4613C11.8442 12.7399 11.5135 12.961 11.1496 13.1118C10.7856 13.2626 10.3955 13.3403 10.0016 13.3403C9.20592 13.3403 8.44286 13.0243 7.88025 12.4617C7.31764 11.899 7.00157 11.136 7.00157 10.3403C7.00157 9.54468 7.31764 8.78162 7.88025 8.21901C8.44286 7.6564 9.20592 7.34033 10.0016 7.34033M15.2516 3.84033C14.92 3.84033 14.6021 3.97203 14.3677 4.20645C14.1333 4.44087 14.0016 4.75881 14.0016 5.09033C14.0016 5.42185 14.1333 5.7398 14.3677 5.97422C14.6021 6.20864 14.92 6.34033 15.2516 6.34033C15.5831 6.34033 15.901 6.20864 16.1355 5.97422C16.3699 5.7398 16.5016 5.42185 16.5016 5.09033C16.5016 4.75881 16.3699 4.44087 16.1355 4.20645C15.901 3.97203 15.5831 3.84033 15.2516 3.84033Z"
                                fill="black" />
                        </svg>
                    </a>
                @endif
                @if(!empty($contacto->fb))
                    <a target="_blank" rel="noopener noreferrer" href="{{ $contacto->fb }}" aria-label="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="21" viewBox="0 0 11 21" fill="none">
                            <path
                                d="M7 11.8403H9.5L10.5 7.84033H7V5.84033C7 4.81033 7 3.84033 9 3.84033H10.5V0.480332C10.174 0.437332 8.943 0.340332 7.643 0.340332C4.928 0.340332 3 1.99733 3 5.04033V7.84033H0V11.8403H3V20.3403H7V11.8403Z"
                                fill="black" />
                        </svg>

                    </a>
                @endif
            </div>
        </div>

        {{-- Secciones - Desktop/Tablet --}}
        <div class="hidden flex-col  gap-6 lg:flex">
            <h2 class="text-[16px] font-semibold text-black">{{__("SECCIONES")}}</h2>
            <div class="grid h-fit grid-flow-col grid-cols-2 grid-rows-5 gap-x-20 gap-y-3">
                <a href="/" class="text-[15px] text-black/80">{{__("Inicio")}}</a>

                <a href="{{ route('productos') }}" class="text-[15px] text-black/80">{{__("Productos")}}</a>
                <a href="{{ route('nosotros') }}" class="text-[15px] text-black/80">{{__("Nosotros")}}</a>

                <a href="{{ route('calidad') }}" class="text-[15px] text-black/80">{{__("Trabaja con nosotros")}}</a>
                <a href="{{ route('donde-comprar.index') }}" class="text-[15px] text-black/80">{{__("Dónde comprar")}}</a>
                <a href="{{ route('comercio.exterior') }}" class="text-[15px] text-black/80">{{__("Comercio exterior")}}</a>
                <a href="{{ route('contacto') }}" class="text-[15px] text-black/80">{{__("Contacto")}}</a>
                <a href="https://proyecto4.ddns.net/proyecto4/login" target="_blank" rel="noopener noreferrer"
                    class="text-[15px] text-black/80">{{__("Área cliente")}}</a>


            </div>
        </div>

        {{-- Secciones - Mobile --}}
        <div class="flex w-full max-w-[360px] flex-col items-center gap-5 sm:hidden">
            <h2 class=" text-black text-[16px] font-semibold">{{__("Secciones")}}</h2>
            <div class="grid w-full grid-cols-2 gap-x-4 gap-y-3 text-center max-[375px]:grid-cols-1">
                <a href="/" class="min-w-0 text-sm text-black/80">{{__("Inicio")}}</a>
                <a href="{{ route('productos') }}" class="min-w-0 text-sm text-black/80">{{__("Productos")}}</a>

                <a href="{{ route('nosotros') }}" class="min-w-0 text-sm text-black/80">{{__("Nosotros")}}</a>
                <a href="{{ route('calidad') }}" class="min-w-0 text-sm text-black/80">{{__("Trabaja con nosotros")}}</a>
                <a href="{{ route('donde-comprar.index') }}" class="min-w-0 text-sm text-black/80">{{__("Dónde comprar")}}</a>
                <a href="{{ route('comercio.exterior') }}" class="min-w-0 text-sm text-black/80">{{__("Comercio exterior")}}</a>
                <a href="{{ route('contacto') }}" class="min-w-0 text-sm text-black/80">{{__("Contacto")}}</a>
                <a href="https://proyecto4.ddns.net/proyecto4/login" target="_blank" rel="noopener noreferrer"
                    class="min-w-0 text-sm text-black/80">{{__("Área cliente")}}</a>
            </div>
        </div>



        {{-- Datos de contacto --}}
        <div class="flex h-full w-full max-w-[326px] flex-col items-center gap-5 text-center lg:items-start lg:text-left">
            <h2 class="text-base font-bold text-black">R.O.S MATERIALES ELÉCTRICOS S.R.L.</h2>
            <div class="flex w-full flex-col justify-center gap-4">
                @if(!empty($contacto->location))
                    <a href="https://maps.google.com/?q={{ urlencode($contacto->location) }}" target="_blank"
                        rel="noopener noreferrer"
                        class="flex min-w-0 items-center gap-3 text-left transition-opacity hover:opacity-80">
                        <div class="shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="20" viewBox="0 0 16 20" fill="none">
                                <path
                                    d="M8 0C5.87904 0.00245748 3.84566 0.831051 2.34592 2.30402C0.846168 3.77699 0.00251067 5.77405 8.51118e-06 7.85714C-0.00253177 9.55945 0.56363 11.2156 1.61164 12.5714C1.61164 12.5714 1.82982 12.8536 1.86546 12.8943L8 20L14.1374 12.8907C14.1694 12.8529 14.3884 12.5714 14.3884 12.5714L14.3891 12.5693C15.4366 11.214 16.0025 9.55866 16 7.85714C15.9975 5.77405 15.1538 3.77699 13.6541 2.30402C12.1543 0.831051 10.121 0.00245748 8 0ZM8 10.7143C7.42464 10.7143 6.86219 10.5467 6.3838 10.2328C5.9054 9.91882 5.53254 9.4726 5.31235 8.95052C5.09217 8.42845 5.03456 7.85397 5.14681 7.29974C5.25906 6.74551 5.53612 6.23642 5.94296 5.83684C6.34981 5.43726 6.86816 5.16514 7.43247 5.0549C7.99677 4.94466 8.58169 5.00124 9.11326 5.21749C9.64483 5.43374 10.0992 5.79994 10.4188 6.2698C10.7385 6.73965 10.9091 7.29205 10.9091 7.85714C10.9081 8.61461 10.6013 9.34079 10.056 9.8764C9.51062 10.412 8.77124 10.7133 8 10.7143Z"
                                    fill="black" />
                            </svg>
                        </div>

                        <p class="min-w-0 break-words text-base text-black/80">{{ $contacto->location }}</p>
                    </a>
                @endif

                @if(!empty($contacto->location_dos))
                    <a href="https://maps.google.com/?q={{ urlencode($contacto->location_dos) }}" target="_blank"
                        rel="noopener noreferrer"
                        class="flex min-w-0 items-center gap-3 text-left transition-opacity hover:opacity-80">
                        <div class="shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M20 10C20 14.993 14.461 20.193 12.601 21.799C12.4277 21.9293 12.2168 21.9998 12 21.9998C11.7832 21.9998 11.5723 21.9293 11.399 21.799C9.539 20.193 4 14.993 4 10C4 7.87827 4.84285 5.84344 6.34315 4.34315C7.84344 2.84285 9.87827 2 12 2C14.1217 2 16.1566 2.84285 17.6569 4.34315C19.1571 5.84344 20 7.87827 20 10Z"
                                    stroke="#487AB7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M12 13C13.6569 13 15 11.6569 15 10C15 8.34315 13.6569 7 12 7C10.3431 7 9 8.34315 9 10C9 11.6569 10.3431 13 12 13Z"
                                    stroke="#487AB7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>

                        <p class="min-w-0 break-words text-base text-black/80">{{ $contacto->location_dos }}</p>
                    </a>
                @endif

                @if(!empty($contacto->mail))
                    <a href="mailto:{{ $contacto->mail }}"
                        class="flex min-w-0 items-center gap-3 transition-opacity hover:opacity-80">
                        <div class="shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="14" viewBox="0 0 18 14" fill="none">
                                <path
                                    d="M16.2 0H1.8C0.81 0 0.00899999 0.7875 0.00899999 1.75L0 12.25C0 13.2125 0.81 14 1.8 14H16.2C17.19 14 18 13.2125 18 12.25V1.75C18 0.7875 17.19 0 16.2 0ZM15.84 3.71875L9.477 7.58625C9.189 7.76125 8.811 7.76125 8.523 7.58625L2.16 3.71875C2.06975 3.6695 1.99073 3.60295 1.9277 3.52315C1.86467 3.44334 1.81896 3.35193 1.79332 3.25445C1.76768 3.15697 1.76265 3.05544 1.77854 2.95602C1.79443 2.85659 1.8309 2.76134 1.88575 2.67601C1.9406 2.59069 2.01269 2.51707 2.09765 2.45962C2.18262 2.40217 2.27868 2.36207 2.38005 2.34176C2.48141 2.32145 2.58595 2.32135 2.68736 2.34145C2.78876 2.36156 2.88492 2.40147 2.97 2.45875L9 6.125L15.03 2.45875C15.1151 2.40147 15.2112 2.36156 15.3126 2.34145C15.414 2.32135 15.5186 2.32145 15.62 2.34176C15.7213 2.36207 15.8174 2.40217 15.9023 2.45962C15.9873 2.51707 16.0594 2.59069 16.1142 2.67601C16.1691 2.76134 16.2056 2.85659 16.2215 2.95602C16.2373 3.05544 16.2323 3.15697 16.2067 3.25445C16.181 3.35193 16.1353 3.44334 16.0723 3.52315C16.0093 3.60295 15.9302 3.6695 15.84 3.71875Z"
                                    fill="black" />
                            </svg>
                        </div>
                        <p class="min-w-0 break-all text-sm text-black/80 sm:break-words sm:text-base">{{ $contacto->mail }}</p>
                    </a>
                @endif

                @if(!empty($contacto->phone))
                    <a href="tel:{{ preg_replace('/\s/', '', $contacto->phone) }}"
                        class="flex min-w-0 items-center gap-3 transition-opacity hover:opacity-80">
                        <div class="shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path
                                    d="M3.37587 6.9248C4.62166 9.44461 6.60335 11.4944 9.0395 12.7829L10.9301 10.8274C11.0424 10.711 11.1839 10.6294 11.3387 10.5917C11.4934 10.554 11.6553 10.5617 11.806 10.614C12.796 10.9504 13.8316 11.1213 14.8736 11.1204C15.1013 11.1211 15.3195 11.215 15.4805 11.3815C15.6415 11.5481 15.7323 11.7737 15.733 12.0092V15.1111C15.7323 15.3466 15.6415 15.5723 15.4805 15.7388C15.3195 15.9054 15.1013 15.9993 14.8736 16C12.9552 16 11.0555 15.6091 9.28307 14.8497C7.51065 14.0903 5.90022 12.9772 4.54372 11.574C3.18723 10.1708 2.11124 8.50491 1.3772 6.67155C0.643158 4.8382 0.265444 2.87324 0.265625 0.888889C0.26635 0.653372 0.357124 0.427715 0.518131 0.261178C0.679139 0.0946416 0.897303 0.000750061 1.125 0H4.1335C4.3612 0.000750061 4.57936 0.0946416 4.74037 0.261178C4.90138 0.427715 4.99215 0.653372 4.99288 0.888889C4.99127 1.96679 5.15652 3.03801 5.48238 4.06187C5.53123 4.21884 5.53705 4.38675 5.49918 4.54693C5.46131 4.70712 5.38125 4.8533 5.26788 4.96924L3.37587 6.9248Z"
                                    fill="black" />
                            </svg>
                        </div>
                        <p class="min-w-0 break-words text-base text-black/80">{{ $contacto->phone }}</p>
                    </a>
                @endif


            </div>
        </div>
    </div>

    {{-- Copyright --}}
    <div
        class="flex  w-full flex-col items-center justify-center bg-[#F2EEED] px-4  text-[14px] text-black/80 sm:flex-row sm:justify-between sm:px-6 lg:px-0 ">
        <div
            class="mx-auto flex w-full max-w-[1224px] flex-col items-center justify-center gap-2 text-center sm:flex-row sm:justify-between sm:gap-0 sm:text-left border-t border-[#C0BBB2] py-4">
            <p>© Copyright {{ date('Y') }} San Justo Iluminación. Todos los derechos reservados.</p>
            <a target="_blank" rel="noopener noreferrer" href="https://osole.com.ar/" class="mt-2 sm:mt-0">
                by OSOLE
            </a>
        </div>
    </div>
</div>

{{-- JavaScript para manejar el formulario del newsletter --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('newsletter-form');
        const successMessage = document.getElementById('newsletter-success');
        const errorMessage = document.getElementById('newsletter-error');
        const errorMessageText = document.getElementById('newsletter-error-message');
        const emailInput = document.getElementById('Email');
        const submitButton = document.getElementById('newsletter-btn');

        // Función para ocultar todos los mensajes
        function hideMessages() {
            successMessage.classList.add('hidden');
            errorMessage.classList.add('hidden');
        }

        // Función para mostrar mensaje de éxito
        function showSuccess() {
            hideMessages();
            successMessage.classList.remove('hidden');
        }

        // Función para mostrar mensaje de error
        function showError(message) {
            hideMessages();
            errorMessageText.textContent = message;
            errorMessage.classList.remove('hidden');
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            // Ocultar mensajes previos
            hideMessages();

            // Deshabilitar el botón para evitar múltiples envíos
            submitButton.disabled = true;
            submitButton.innerHTML = '<svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

            // Crear FormData con los datos del formulario
            const formData = new FormData(form);

            // Realizar petición AJAX
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mostrar mensaje de éxito
                        showSuccess();

                        // Limpiar el campo de email
                        emailInput.value = '';

                        // Ocultar el mensaje después de 5 segundos
                        setTimeout(function () {
                            hideMessages();
                        }, 5000);
                    } else {
                        // Manejar errores de validación
                        let errorMsg = 'Ocurrió un error al procesar tu solicitud';

                        if (data.errors && data.errors.email) {
                            errorMsg = data.errors.email[0];
                        } else if (data.message) {
                            errorMsg = data.message;
                        }

                        showError(errorMsg);

                        // Ocultar el mensaje de error después de 5 segundos
                        setTimeout(function () {
                            hideMessages();
                        }, 5000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('Error de conexión. Por favor, intenta nuevamente.');

                    // Ocultar el mensaje de error después de 5 segundos
                    setTimeout(function () {
                        hideMessages();
                    }, 5000);
                })
                .finally(() => {
                    // Rehabilitar el botón
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M1 8H15M15 8L8 1M15 8L8 15" stroke="#0072C6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>';
                });
        });
    });
</script>

{{-- Incluir Font Awesome si no está ya incluido --}}
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush
