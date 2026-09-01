@extends('layouts.default')

@section('title', 'Trabaja con nosotros - San Justo Iluminacion')

@section('description', $metadatos->description ?? "")
@section('keywords', $metadatos->keywords ?? "")

@section('content')

    <div
        class="absolute top-30 max-sm:top-5 left-1/2 w-[1224px] max-sm:w-full max-sm:px-4 -translate-x-1/2 flex flex-row gap-1 z-100">
        <a href="/" class="text-black font-medium text-[12px]">{{__('Inicio')}}</a>
        <span class="text-black font-medium text-[12px]">/</span>
        <span class="text-black font-medium text-[12px]">{{__('Trabajá con nosotros')}}</span>
    </div>

    <div class="w-[1224px] max-sm:w-full max-sm:px-4 mx-auto min-h-[40vh] py-20 max-sm:py-8 flex flex-col gap-14 max-sm:gap-8"
        x-data="{ 
                                                                                        showModal: false,
                                                                                        isLoading: false,
                                                                                        error: '',
                                                                                        async submitForm() {
                                                                                            this.isLoading = true;
                                                                                            this.error = '';

                                                                                            const formData = new FormData(this.$refs.workForm);

                                                                                            try {
                                                                                                const response = await fetch('{{ route('trabaja.enviar') }}', {
                                                                                                    method: 'POST',
                                                                                                    body: formData,
                                                                                                    headers: {
                                                                                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                                                                                        'Accept': 'application/json'
                                                                                                    }
                                                                                                });

                                                                                                const data = await response.json();

                                                                                                if (response.ok) {
                                                                                                    this.showModal = true;
                                                                                                    this.$refs.workForm.reset();
                                                                                                } else {
                                                                                                    // Manejar errores de validación
                                                                                                    if (data.errors) {
                                                                                                        this.error = Object.values(data.errors).flat().join(', ');
                                                                                                    } else {
                                                                                                        this.error = data.message || '{{__("Hubo un error al enviar el formulario")}}';
                                                                                                    }
                                                                                                }
                                                                                            } catch (error) {
                                                                                                console.error('Error:', error);
                                                                                                this.error = '{{__("Error de conexión. Inténtalo de nuevo.")}}';
                                                                                            } finally {
                                                                                                this.isLoading = false;
                                                                                            }
                                                                                        }
                                                                                    }">
        <div class="flex flex-col">
            <h2 class="text-[32px] max-sm:text-[24px] font-semibold font-custom!">
                {{request('lang') == 'en' ? $trabaja->title_en : $trabaja->title_es}}
            </h2>
            <div class="text-[20px]! max-sm:text-[16px]! break-words max-w-[863px] max-sm:max-w-full">
                {!! request('lang') == 'en' ? $trabaja->text_en : $trabaja->text_es !!}
            </div>
        </div>

        <div class="w-full flex justify-center">
            <form x-ref="workForm" @submit.prevent="submitForm"
                class="grid grid-cols-2 max-sm:grid-cols-1 gap-5 max-sm:gap-4">
                @csrf
                <!-- Mostrar mensaje de error si existe -->
                <div x-show="error" x-transition
                    class="col-span-2 max-sm:col-span-1 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                    role="alert">
                    <span x-text="error"></span>
                </div>

                <div class="flex flex-col gap-2">
                    <label for="nombre">{{__('Nombre') . " *"}}</label>
                    <input type="text" id="nombre" name="nombre"
                        class="border h-[49px] w-[500px] max-sm:w-full rounded-sm pl-4" required>
                </div>
                <div class="flex flex-col gap-2">
                    <label for="apellido">{{__('Apellido') . " *"}}</label>
                    <input type="text" id="apellido" name="apellido"
                        class="border h-[49px] w-[500px] max-sm:w-full rounded-sm pl-4" required>
                </div>
                <div class="flex flex-col gap-2">
                    <label for="telefono">{{__('Telefono') . " *"}}</label>
                    <input type="text" id="telefono" name="telefono"
                        class="border h-[49px] w-[500px] max-sm:w-full rounded-sm pl-4" required>
                </div>
                <div class="flex flex-col gap-2">
                    <label for="email">{{__('Correo electrónico')}} *</label>
                    <input type="email" id="email" name="email"
                        class="border h-[49px] w-[500px] max-sm:w-full rounded-sm pl-4" required>
                </div>
                <div class="flex flex-col gap-2">
                    <label for="archivo">{{__("Adjunta tu CV") . " *"}}</label>
                    <div
                        class="flex flex-row border h-[49px] w-[500px] max-sm:w-full rounded-sm items-center justify-between px-4">
                        <p class="text-[16px] max-sm:text-[14px] text-[#7C7C7C] max-sm:truncate">
                            {{__('Podés cargar PDF, Word, PNG de hasta 2MB')}}</p>
                        <label class="cursor-pointer flex-shrink-0" for="archivo">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="#0049a0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-upload-icon lucide-upload">
                                <path d="M12 3v12" />
                                <path d="m17 8-5-5-5 5" />
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            </svg>
                        </label>
                    </div>
                    <input type="file" id="archivo" name="archivo" class="hidden" required>
                </div>

                <button type="submit" :disabled="isLoading" :class="isLoading ? 'opacity-50 cursor-not-allowed' : ''"
                    class="w-full text-white bg-primary-orange rounded-sm h-[49px] self-end text-[14px] font-medium max-sm:col-span-1">
                    <span x-show="!isLoading">{{__('ENVIAR')}}</span>
                    <span x-show="isLoading" class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        {{__('Enviando...')}}
                    </span>
                </button>

            </form>

        </div>
        <div class="w-[1020px] max-sm:w-full mx-auto max-sm:text-center">
            <a href="mailto:{{ $trabaja->email }}" class="text-[16px] max-sm:text-[14px] font-medium mt-4">
                {{__("Tambien podes enviarlo por mail a")}}
                {{ ": " . $trabaja->email}}
            </a>
        </div>

        <!-- Modal -->
        <div x-show="showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 max-sm:px-4" style="display: none;">

            <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95" @click.away="showModal = false"
                class="bg-white rounded-sm w-[600px] max-sm:w-full h-[297px] max-sm:h-auto max-sm:min-h-[250px] relative">

                <!-- Botón cerrar -->
                <button @click="showModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>

                <!-- Contenido del modal -->
                <div
                    class="text-center flex justify-center items-center flex-col gap-4 w-[600px] max-sm:w-full h-[297px] max-sm:h-auto max-sm:min-h-[250px] max-sm:p-6">
                    <!-- Ícono de éxito -->

                    <h3 class="text-[30px] max-sm:text-[24px] font-semibold font-custom!">
                        {{__('¡Gracias por postularte!')}}
                    </h3>

                    <p class="text-[20px] max-sm:text-[16px] font-medium max-w-[462px] max-sm:max-w-full max-sm:px-4">
                        {{__('Recibimos tu información correctamente.
                                                                                                                                        Si tu perfil se ajusta a nuestras búsquedas, nos vamos a estar contactando con vos.')}}
                    </p>

                </div>
            </div>
        </div>
    </div>

@endsection