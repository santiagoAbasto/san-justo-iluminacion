@extends('layouts.default')

@section('title', 'Contacto -  San Justo Iluminacion')

@section('description', $metadatos->description ?? "")
@section('keywords', $metadatos->keywords ?? "")

@push('head')
    <meta name="description" content="{{ $metadatos->description ?? '' }}">
    <meta name="keywords" content="{{ $metadatos->keywords ?? '' }}">
@endpush

@section('content')

    <div
        class="mx-auto flex w-full max-w-[1224px] max-sm:max-w-full flex-col gap-6 px-4 py-10 md:gap-10 md:px-0 md:py-20 max-sm:py-8">

        {{-- Mensaje de éxito --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 max-sm:mb-4" role="alert">
                <div class="flex">
                    <div class="py-1">
                        <svg class="fill-current h-6 w-6 max-sm:h-5 max-sm:w-5 text-green-500 mr-4 max-sm:mr-3"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path
                                d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold max-sm:text-[14px]">¡Éxito!</p>
                        <p class="text-sm max-sm:text-[12px]">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Mensaje de error general --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 max-sm:mb-4" role="alert">
                <div class="flex">
                    <div class="py-1">
                        <svg class="fill-current h-6 w-6 max-sm:h-5 max-sm:w-5 text-red-500 mr-4 max-sm:mr-3"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path
                                d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm1.41-1.41A8 8 0 1 0 15.66 4.34 8 8 0 0 0 4.34 15.66zm9.9-8.49L11.41 10l2.83 2.83-1.41 1.41L10 11.41l-2.83 2.83-1.41-1.41L8.59 10 5.76 7.17l1.41-1.41L10 8.59l2.83-2.83 1.41 1.41z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold max-sm:text-[14px]">Error</p>
                        <p class="text-sm max-sm:text-[12px]">Por favor, revisa los campos del formulario.</p>
                    </div>
                </div>
            </div>
        @endif

        <div
            class="absolute top-30 max-sm:top-5 left-1/2 w-[1224px] max-sm:w-full max-sm:px-4 -translate-x-1/2 flex flex-row gap-1 z-100">
            <a href="/" class="text-black font-medium text-[12px]">{{__('Inicio')}}</a>
            <span class="text-black font-medium text-[12px]">/</span>
            <span class="text-black font-medium text-[12px]">{{__('Contacto')}}</span>
        </div>

        <div class="flex flex-col mb-10 max-sm:mb-6">
            <h2 class="text-[32px] max-sm:text-[24px] font-semibold font-custom!">
                {{request('lang') == 'en' ? $contacto->title_en : $contacto->title_es}}
            </h2>
            <p class="text-[20px]! max-sm:text-[16px]! max-w-[903px] max-sm:max-w-full">
                {!! request('lang') == 'en' ? $contacto->text_en : $contacto->text_es !!}
            </p>
        </div>

        <div class="flex flex-col gap-8 md:flex-row md:gap-0 max-sm:flex-col max-sm:gap-6">
            {{-- Contacto info --}}

            <div class="mb-6 max-sm:mb-4 flex w-full flex-col gap-4 max-sm:gap-3 md:mb-0 md:w-1/3">
                @php
                    $datos = [
                        [
                            'name' => $contacto->location ?? "",
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" style="min-width: 16px" width="16" height="20" viewBox="0 0 16 20" fill="none">
                                                                                  <path d="M8 0C5.87904 0.00245748 3.84566 0.831051 2.34592 2.30402C0.846168 3.77699 0.00251067 5.77405 8.51118e-06 7.85714C-0.00253177 9.55945 0.56363 11.2156 1.61164 12.5714C1.61164 12.5714 1.82982 12.8536 1.86546 12.8943L8 20L14.1374 12.8907C14.1694 12.8529 14.3884 12.5714 14.3884 12.5714L14.3891 12.5693C15.4366 11.214 16.0025 9.55866 16 7.85714C15.9975 5.77405 15.1538 3.77699 13.6541 2.30402C12.1543 0.831051 10.121 0.00245748 8 0ZM8 10.7143C7.42464 10.7143 6.86219 10.5467 6.3838 10.2328C5.9054 9.91882 5.53254 9.4726 5.31235 8.95052C5.09217 8.42845 5.03456 7.85397 5.14681 7.29974C5.25906 6.74551 5.53612 6.23642 5.94296 5.83684C6.34981 5.43726 6.86816 5.16514 7.43247 5.0549C7.99677 4.94466 8.58169 5.00124 9.11326 5.21749C9.64483 5.43374 10.0992 5.79994 10.4188 6.2698C10.7385 6.73965 10.9091 7.29205 10.9091 7.85714C10.9081 8.61461 10.6013 9.34079 10.056 9.8764C9.51062 10.412 8.77124 10.7133 8 10.7143Z" fill="#0049A0"/>
                                                                                </svg>',
                            'href' => 'https://maps.google.com/?q=' . urlencode($contacto->location)
                        ],
                        [
                            'name' => $contacto->mail ?? "",
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="14" viewBox="0 0 18 14" fill="none">
                                                              <path d="M16.2 0H1.8C0.81 0 0.00899999 0.7875 0.00899999 1.75L0 12.25C0 13.2125 0.81 14 1.8 14H16.2C17.19 14 18 13.2125 18 12.25V1.75C18 0.7875 17.19 0 16.2 0ZM15.84 3.71875L9.477 7.58625C9.189 7.76125 8.811 7.76125 8.523 7.58625L2.16 3.71875C2.06975 3.6695 1.99073 3.60295 1.9277 3.52315C1.86467 3.44334 1.81896 3.35193 1.79332 3.25445C1.76768 3.15697 1.76265 3.05544 1.77854 2.95602C1.79443 2.85659 1.8309 2.76134 1.88575 2.67601C1.9406 2.59069 2.01269 2.51707 2.09765 2.45962C2.18262 2.40217 2.27868 2.36207 2.38005 2.34176C2.48141 2.32145 2.58595 2.32135 2.68736 2.34145C2.78876 2.36156 2.88492 2.40147 2.97 2.45875L9 6.125L15.03 2.45875C15.1151 2.40147 15.2112 2.36156 15.3126 2.34145C15.414 2.32135 15.5186 2.32145 15.62 2.34176C15.7213 2.36207 15.8174 2.40217 15.9023 2.45962C15.9873 2.51707 16.0594 2.59069 16.1142 2.67601C16.1691 2.76134 16.2056 2.85659 16.2215 2.95602C16.2373 3.05544 16.2323 3.15697 16.2067 3.25445C16.181 3.35193 16.1353 3.44334 16.0723 3.52315C16.0093 3.60295 15.9302 3.6695 15.84 3.71875Z" fill="#0049A0"/>
                                                            </svg>',
                            'href' => 'mailto:' . $contacto->mail ?? ''
                        ],
                        [
                            'name' => $contacto->phone ?? "",
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                              <path d="M3.37587 6.9248C4.62166 9.44461 6.60335 11.4944 9.0395 12.7829L10.9301 10.8274C11.0424 10.711 11.1839 10.6294 11.3387 10.5917C11.4934 10.554 11.6553 10.5617 11.806 10.614C12.796 10.9504 13.8316 11.1213 14.8736 11.1204C15.1013 11.1211 15.3195 11.215 15.4805 11.3815C15.6415 11.5481 15.7323 11.7737 15.733 12.0092V15.1111C15.7323 15.3466 15.6415 15.5723 15.4805 15.7388C15.3195 15.9054 15.1013 15.9993 14.8736 16C12.9552 16 11.0555 15.6091 9.28307 14.8497C7.51065 14.0903 5.90022 12.9772 4.54372 11.574C3.18723 10.1708 2.11124 8.50491 1.3772 6.67155C0.643158 4.8382 0.265444 2.87324 0.265625 0.888889C0.26635 0.653372 0.357124 0.427715 0.518131 0.261178C0.679139 0.0946416 0.897303 0.000750061 1.125 0H4.1335C4.3612 0.000750061 4.57936 0.0946416 4.74037 0.261178C4.90138 0.427715 4.99215 0.653372 4.99288 0.888889C4.99127 1.96679 5.15652 3.03801 5.48238 4.06187C5.53123 4.21884 5.53705 4.38675 5.49918 4.54693C5.46131 4.70712 5.38125 4.8533 5.26788 4.96924L3.37587 6.9248Z" fill="#0049A0"/>
                                                            </svg>',
                            'href' => 'tel:' . preg_replace('/\s+/', '', $contacto->phone)
                        ],


                    ];
                @endphp
                <h2 class="text-[26px] max-sm:text-[20px] font-semibold font-custom!">{{__("Dónde podés encontrarnos")}}
                </h2>
                @foreach ($datos as $dato)
                    <a href="{{ $dato['href'] }}" target="_blank"
                        class="flex flex-row items-center gap-3 max-sm:gap-2 transition-opacity hover:opacity-80">
                        {!! $dato['icon'] !!}
                        <p class="text-base max-sm:text-[14px]">{{ $dato['name'] }}
                        </p>
                    </a>
                @endforeach
            </div>

            {{-- Formulario --}}
            <form id="contactForm" method="POST" action="{{ route('send.contact') }}"
                class="grid w-full grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-x-5 sm:gap-y-10 max-sm:gap-y-4 md:w-2/3">
                @csrf
                <div class="flex flex-col gap-2 sm:gap-3 max-sm:gap-2">
                    <label for="name" class="text-base max-sm:text-[14px]">{{__("Nombre")}} *</label>
                    <input required type="text" name="name" id="name"
                        class="h-[44px] max-sm:h-[40px] w-full border border-[#EEEEEE] pl-3 rounded-sm"
                        value="{{ old('name') }}">
                    @error('name') <p class="text-red-500 text-sm max-sm:text-[12px]">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-2 sm:gap-3 max-sm:gap-2">
                    <label for="apellido" class="text-base max-sm:text-[14px]">{{__("Apellido")}} *</label>
                    <input required type="text" name="apellido" id="apellido"
                        class="h-[44px] max-sm:h-[40px] w-full border border-[#EEEEEE] pl-3 rounded-sm"
                        value="{{ old('apellido') }}">
                    @error('apellido') <p class="text-red-500 text-sm max-sm:text-[12px]">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-2 sm:gap-3 max-sm:gap-2">
                    <label for="telefono" class="text-base max-sm:text-[14px]">{{__("Empresa / Ocupación")}} *</label>
                    <input required type="text" name="empresa" id="empresa"
                        class="h-[44px] max-sm:h-[40px] w-full border border-[#EEEEEE] pl-3 rounded-sm"
                        value="{{ old('empresa') }}">
                    @error('empresa') <p class="text-red-500 text-sm max-sm:text-[12px]">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-2 sm:gap-3 max-sm:gap-2">
                    <label for="email" class="text-base max-sm:text-[14px]">{{__("Correo electrónico")}} *</label>
                    <input required type="text" name="email" id="email"
                        class="h-[44px] max-sm:h-[40px] w-full border border-[#EEEEEE] pl-3 rounded-sm"
                        value="{{ old('email') }}">
                    @error('email') <p class="text-red-500 text-sm max-sm:text-[12px]">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-2 sm:gap-3 max-sm:gap-2">
                    <label for="telefono" class="text-base max-sm:text-[14px]">{{__("Telefono")}} *</label>
                    <input required type="text" name="telefono" id="telefono"
                        class="h-[44px] max-sm:h-[40px] w-full border border-[#EEEEEE] pl-3 rounded-sm"
                        value="{{ old('telefono') }}">
                    @error('telefono') <p class="text-red-500 text-sm max-sm:text-[12px]">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-2 sm:gap-3 max-sm:gap-2">
                    <label for="como_llegaste" class="text-base max-sm:text-[14px]">{{__("¿Cómo llegaste a nosotros?")}}
                        *</label>
                    <select class="h-[44px] max-sm:h-[40px] w-full border border-[#EEEEEE] pl-3 rounded-sm"
                        name="como_llegaste" id="como_llegaste">
                        <option value="">{{__("Seleccione una opción")}}</option>
                        <option value="buscador">{{__("Buscador")}}</option>
                        <option value="redes_sociales">{{__("Redes Sociales")}}</option>
                        <option value="recomendacion">{{__("Recomendación")}}</option>
                        <option value="otro">{{__("Otro")}}</option>
                    </select>
                    @error('como_llegaste') <p class="text-red-500 text-sm max-sm:text-[12px]">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-2 sm:gap-3 max-sm:gap-2 col-span-2 max-sm:col-span-1">
                    <label for="mensaje" class="text-base max-sm:text-[14px]">{{__("Comentario")}} </label>
                    <textarea required name="mensaje" id="mensaje"
                        class="h-[150px] max-sm:h-[120px] w-full border border-[#EEEEEE] pt-2 pl-3 rounded-sm">{{ $mensaje ? "Buenas tardes queria recibir informacion acerca de " . $mensaje : '' }}</textarea>
                    @error('mensaje') <p class="text-red-500 text-sm max-sm:text-[12px]">{{ $message }}</p> @enderror
                </div>


                <p class="text-base max-sm:text-[14px]">*{{__("Campos obligatorios")}}</p>
                <button form="contactForm" type="submit"
                    class="bg-primary-orange text-bold min-h-[41px] max-sm:min-h-[38px] w-full text-[16px] max-sm:text-[14px] text-white rounded-sm">{{__("Enviar")}}</button>

            </form>
        </div>

        {{-- Mapa --}}

    </div>



    <div class="w-full">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3273.4154200822118!2d-58.667692900000006!3d-34.8709133!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bcc7765b66b581%3A0xbd9833c2577c9817!2sSan%20Justo%20Iluminaci%C3%B3n!5e0!3m2!1ses-419!2sar!4v1757606049575!5m2!1ses-419!2sar"
            class="w-full h-[500px] max-sm:h-[300px] rounded-sm" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <script>
        // Auto-cerrar mensaje después de 5 segundos
        setTimeout(function () {
            const successAlert = document.querySelector('.bg-green-100');
            if (successAlert) {
                successAlert.style.display = 'none';
            }
        }, 5000);
    </script>
@endsection