<div class="max-w-[1224px] mx-auto h-fit max-lg:min-h-0 gap-5 flex flex-col justify-center max-lg:py-4">
    <h2 class="text-[32px] font-semibold font-custom!">{{__("Descubrí nuestra línea de productos")}}</h2>
    <form action="{{ route('productos') }}" method="GET"
        class="flex flex-col lg:flex-row gap-6 max-sm:gap-4 w-[1224px] max-xl:w-full max-xl:px-6 max-lg:px-4 max-sm:px-4 mx-auto h-auto items-start lg:items-center">

        <!-- Sección: Por vehículo / Código -->
        <div class="flex flex-col w-full gap-4 max-sm:gap-3">

            <!-- Contenedor de campos -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4 max-sm:gap-3 w-full">
                <!-- Espacio -->
                <div class="flex flex-col gap-2 w-full">
                    <label for="espacio" class="text-[16px] max-sm:text-[14px] font-medium">{{__("Espacio")}}</label>
                    <div class="relative">
                        <select value="{{ $espacio ?? '' }}"
                            class="rounded-sm bg-white p-2 pr-10 focus:outline focus:outline-primary-orange transition duration-300 w-full text-sm max-sm:text-xs outline outline-gray-300"
                            name="espacio" id="espacio">
                            <option value="">{{(__("Elegir espacio"))}}</option>
                            @foreach ($espacios as $espacioItem)
                                <option value="{{ $espacioItem->id }}" {{ ($espacio ?? '') == $espacioItem->id ? 'selected' : '' }}>
                                    {{ request('lang') == 'en' ? $espacioItem->name_en : $espacioItem->name_es }}
                                </option>
                            @endforeach
                        </select>
                        @if($espacio ?? '')
                            <a href="{{ route('productos', array_filter(request()->except(['espacio', 'uso']))) }}"
                                class="absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 transition duration-200"
                                title="Eliminar filtro">
                                <svg class="w-4 h-4 max-sm:w-3 max-sm:h-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Uso -->
                <div class="flex flex-col gap-2 w-full">
                    <label for="uso" class="text-[16px] max-sm:text-[14px] font-medium">{{__("Uso")}}</label>
                    <div class="relative">
                        <select value="{{ $uso ?? '' }}"
                            class="rounded-sm bg-white p-2 pr-10 focus:outline focus:outline-primary-orange transition duration-300 w-full text-sm max-sm:text-xs outline outline-gray-300"
                            name="uso" id="uso">
                            <option value="">{{__("Elegir uso")}}</option>
                            @foreach ($usos as $usoItem)
                                <option value="{{ $usoItem->id }}" {{ ($uso ?? '') == $usoItem->id ? 'selected' : '' }}>
                                    {{ request('lang') == 'en' ? $usoItem->name_en : $usoItem->name_es }}
                                </option>
                            @endforeach
                        </select>
                        @if($uso ?? '')
                            <a href="{{ route('productos', array_filter(request()->except('uso'))) }}"
                                class="absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 transition duration-200"
                                title="Eliminar filtro">
                                <svg class="w-4 h-4 max-sm:w-3 max-sm:h-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Línea -->
                <div class="flex flex-col gap-2 w-full">
                    <label for="linea" class="text-[16px] max-sm:text-[14px] font-medium">{{__("Línea")}}</label>
                    <div class="relative">
                        <select value="{{ $linea ?? '' }}"
                            class="rounded-sm bg-white p-2 pr-10 focus:outline focus:outline-primary-orange transition duration-300 w-full text-sm max-sm:text-xs outline outline-gray-300"
                            name="linea" id="linea">
                            <option value="">{{__("Elegir linea")}}</option>
                            @foreach ($lineas as $lineaItem)
                                <option value="{{ $lineaItem->id }}" {{ ($linea ?? '') == $lineaItem->id ? 'selected' : '' }}>
                                    {{ request('lang') == 'en' ? $lineaItem->name_en : $lineaItem->name_es }}
                                </option>
                            @endforeach
                        </select>
                        @if($linea ?? '')
                            <a href="{{ route('productos', array_filter(request()->except(['linea', 'ambiente']))) }}"
                                class="absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 transition duration-200"
                                title="Eliminar filtro">
                                <svg class="w-4 h-4 max-sm:w-3 max-sm:h-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Ambiente -->
                <div class="flex flex-col gap-2 w-full">
                    <label for="ambiente" class="text-[16px] max-sm:text-[14px] font-medium">{{__("Ambiente")}}</label>
                    <div class="relative">
                        <select value="{{ $ambiente ?? '' }}"
                            class="rounded-sm bg-white p-2 pr-10 focus:outline focus:outline-primary-orange transition duration-300 w-full text-sm max-sm:text-xs outline outline-gray-300"
                            name="ambiente" id="ambiente">
                            <option value="">{{__("Elegir ambiente")}}</option>
                            @foreach ($ambientes as $ambienteItem)
                                <option value="{{ $ambienteItem->id }}" {{ ($ambiente ?? '') == $ambienteItem->id ? 'selected' : '' }}>
                                    {{ request('lang') == 'en' ? $ambienteItem->name_en : $ambienteItem->name_es }}
                                </option>
                            @endforeach
                        </select>
                        @if($ambiente ?? '')
                            <a href="{{ route('productos', array_filter(request()->except('ambiente'))) }}"
                                class="absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 transition duration-200"
                                title="Eliminar filtro">
                                <svg class="w-4 h-4 max-sm:w-3 max-sm:h-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Código Original -->
                <div class="flex flex-col gap-2 w-full">
                    <label for="codigo_original"
                        class="text-[16px] max-sm:text-[14px] font-medium">{{__("Código")}}</label>
                    <div class="relative">
                        <input value="{{ $code ?? '' }}" type="text"
                            class="rounded-sm bg-white p-2 pr-10 focus:outline focus:outline-primary-orange transition duration-300 w-full text-sm max-sm:text-xs outline outline-gray-300"
                            id="codigo_original" name="code" placeholder="{{__('Ingrese código')}}">
                        @if($code ?? '')
                            <a href="{{ route('productos', array_filter(request()->except('code'))) }}"
                                class="absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 transition duration-200"
                                title="Eliminar filtro">
                                <svg class="w-4 h-4 max-sm:w-3 max-sm:h-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>


            </div>
        </div>

        <!-- Botón de búsqueda -->
        <div
            class="flex flex-col items-center self-end h-full lg:items-end justify-end w-full lg:w-fit gap-2 mt-4 lg:mt-0">
            <button type="submit"
                class="bg-primary-orange text-white rounded-sm px-6 py-2 max-sm:px-4 max-sm:py-1.5 text-[16px] max-sm:text-[14px] font-semibold hover:bg-primary-orange-dark transition duration-300 w-full lg:w-auto min-w-[120px] max-sm:min-w-[100px]">
                {{__("Buscar")}}
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const espacioSelect = document.getElementById('espacio');
        const usoSelect = document.getElementById('uso');
        const lineaSelect = document.getElementById('linea');
        const ambienteSelect = document.getElementById('ambiente');
        const currentLang = '{{ request("lang", "es") }}';

        // Función para actualizar las opciones de un select
        function updateSelectOptions(selectElement, data, selectedValue = '', defaultText = '') {
            selectElement.innerHTML = `<option value="">${defaultText}</option>`;

            data.forEach(item => {
                const text = currentLang === 'en' && item.name_en ? item.name_en : item.name_es;
                const option = new Option(text, item.id);
                if (item.id == selectedValue) {
                    option.selected = true;
                }
                selectElement.add(option);
            });
        }

        // Función para mostrar estado de carga
        function showLoading(selectElement, text) {
            selectElement.innerHTML = `<option value="">${text}...</option>`;
            selectElement.disabled = true;
        }

        // Función para habilitar select
        function enableSelect(selectElement) {
            selectElement.disabled = false;
        }

        // Manejar cambio en Espacio
        espacioSelect.addEventListener('change', function () {
            const espacioId = this.value;
            const currentUso = '{{ $uso ?? "" }}';

            if (espacioId) {
                showLoading(usoSelect, 'Cargando usos');

                fetch(`{{ route('api.usos.by.espacio') }}?espacio_id=${espacioId}`)
                    .then(response => response.json())
                    .then(data => {
                        updateSelectOptions(usoSelect, data, currentUso, 'Elegir uso');
                        enableSelect(usoSelect);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        usoSelect.innerHTML = '<option value="">Error al cargar usos</option>';
                        enableSelect(usoSelect);
                    });
            } else {
                // Si no hay espacio seleccionado, cargar todos los usos
                showLoading(usoSelect, 'Cargando usos');

                fetch(`{{ route('api.usos.by.espacio') }}`)
                    .then(response => response.json())
                    .then(data => {
                        updateSelectOptions(usoSelect, data, '', 'Elegir uso');
                        enableSelect(usoSelect);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        usoSelect.innerHTML = '<option value="">Error al cargar usos</option>';
                        enableSelect(usoSelect);
                    });
            }
        });

        // Manejar cambio en Línea
        lineaSelect.addEventListener('change', function () {
            const lineaId = this.value;
            const currentAmbiente = '{{ $ambiente ?? "" }}';

            if (lineaId) {
                showLoading(ambienteSelect, 'Cargando ambientes');

                fetch(`{{ route('api.ambientes.by.linea') }}?linea_id=${lineaId}`)
                    .then(response => response.json())
                    .then(data => {
                        updateSelectOptions(ambienteSelect, data, currentAmbiente, 'Elegir ambiente');
                        enableSelect(ambienteSelect);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        ambienteSelect.innerHTML = '<option value="">Error al cargar ambientes</option>';
                        enableSelect(ambienteSelect);
                    });
            } else {
                // Si no hay línea seleccionada, cargar todos los ambientes
                showLoading(ambienteSelect, 'Cargando ambientes');

                fetch(`{{ route('api.ambientes.by.linea') }}`)
                    .then(response => response.json())
                    .then(data => {
                        updateSelectOptions(ambienteSelect, data, '', 'Elegir ambiente');
                        enableSelect(ambienteSelect);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        ambienteSelect.innerHTML = '<option value="">Error al cargar ambientes</option>';
                        enableSelect(ambienteSelect);
                    });
            }
        });
    });
</script>