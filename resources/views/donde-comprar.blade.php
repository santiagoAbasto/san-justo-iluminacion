@extends('layouts.default')

@section('title', 'Donde comprar - San Justo Iluminación')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #mapa {
            height: 500px;
            width: 100%;
            filter: grayscale(100%);
        }

        @media (max-width: 640px) {
            #mapa {
                height: 350px;
            }
        }

        .punto-venta-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .punto-venta-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .punto-venta-card.activo {
            @apply border-blue-500 ring-2 ring-blue-200;
        }
    </style>
@endpush

@section('content')

    <div
        class="absolute top-30 max-sm:top-5 left-1/2 w-[1224px] max-sm:w-full max-sm:px-4 -translate-x-1/2 flex flex-row gap-1 z-100">
        <a href="/" class="text-black font-medium text-[12px]">{{ __('Inicio') }}</a>
        <span class="text-black font-medium text-[12px]">/</span>
        <span class="text-black font-medium text-[12px]">{{ __('Donde comprar') }}</span>
    </div>
    <div class="w-[1224px] max-sm:w-full max-sm:px-4 mx-auto py-20 max-sm:py-8" x-data="mapaComponent()">
        <!-- Header -->
        <div class="mb-8 max-sm:mb-6">
            <h1 class="text-[32px] max-sm:text-[24px] font-semibold font-custom! mb-2">
                {{ request('lang') == 'en' ? $contenido->title_en : $contenido->title_es }}
            </h1>
            <div class="text-[20px]! max-sm:text-[16px]!">
                {!! request('lang') == 'en' ? $contenido->text_en : $contenido->text_es !!}
            </div>
        </div>

        <!-- Filtros -->
        <div class="grid grid-cols-1 md:grid-cols-12 max-sm:grid-cols-1 gap-4 mb-10 max-sm:mb-6">
            <div class="md:col-span-4 max-sm:col-span-1">
                <label class="block text-[16px] max-sm:text-[14px] font-semibold mb-2">Nombre</label>
                <select
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    x-model="filtros.nombre" @change="filtrar()">
                    <option value="">Todos los nombres</option>
                    <template x-for="nombre in nombresUnicos" :key="nombre">
                        <option :value="nombre" x-text="nombre"></option>
                    </template>
                </select>
            </div>
            <div class="md:col-span-4 max-sm:col-span-1">
                <label class="block text-[16px] max-sm:text-[14px] font-semibold mb-2">Provincia</label>
                <select
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    x-model="filtros.provincia" @change="filtrarPorProvincia()">
                    <option value="">Todas las provincias</option>
                    @foreach ($provincias as $prov)
                        <option value="{{ $prov->name }}" {{ $provincia == $prov->name ? 'selected' : '' }}>
                            {{ $prov->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-4 max-sm:col-span-1">
                <label class="block text-[16px] max-sm:text-[14px] font-semibold mb-2">Ciudad</label>
                <select
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                    x-model="filtros.localidad" @change="filtrar()" :disabled="!filtros.provincia">
                    <option value="">Todas las localidades</option>
                    <template x-for="loc in localidades" :key="loc.name || loc">
                        <option :value="loc.name || loc" x-text="loc.name || loc"
                            :selected="(loc.name || loc) === '{{ $localidad }}'"></option>
                    </template>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 max-sm:grid-cols-1 gap-6 max-sm:gap-4">
            <!-- Sidebar con listado -->
            <div class="lg:col-span-4 max-sm:col-span-1">
                <div class="bg-white h-[705px] max-sm:h-[400px] overflow-hidden">
                    <div class="h-full overflow-y-auto">
                        <template x-for="punto in puntosVentaFiltrados" :key="punto.id">
                            <div class="punto-venta-card p-4 max-sm:p-3 border-b border-gray-100 hover:bg-gray-50"
                                :class="{ 'activo bg-blue-50': puntoSeleccionado?.id === punto.id }"
                                @click="seleccionarPunto(punto)">
                                <h3 class="font-semibold text-gray-900 mb-1 max-sm:text-[14px]" x-text="punto.nombre"></h3>
                                <p class="text-sm max-sm:text-[12px] text-gray-600 mb-1" x-text="punto.direccion"></p>
                                <p class="text-sm max-sm:text-[12px] text-gray-500 mb-2">
                                    <span x-text="punto.localidad"></span> 
                                </p>
                                <div x-show="punto.telefono"
                                    class="text-sm max-sm:text-[12px] text-gray-600 flex items-center mb-1">
                                    <svg class="w-4 h-4 max-sm:w-3 max-sm:h-3 mr-2 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg>
                                    <span x-text="punto.telefono"></span>
                                </div>
                                <div x-show="punto.email"
                                    class="text-sm max-sm:text-[12px] text-gray-600 flex items-center">
                                    <svg class="w-4 h-4 max-sm:w-3 max-sm:h-3 mr-2 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span x-text="punto.email" class="break-all"></span>
                                </div>
                            </div>
                        </template>
                        <div x-show="puntosVentaFiltrados.length === 0" class="p-6 max-sm:p-4 text-center">
                            <svg class="w-12 h-12 max-sm:w-8 max-sm:h-8 text-gray-300 mx-auto mb-4" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <p class="text-gray-500 font-medium max-sm:text-[14px]">No se encontraron puntos de venta</p>
                            <p class="text-sm max-sm:text-[12px] text-gray-400">Intenta ajustar los filtros de búsqueda</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mapa -->
            <div class="lg:col-span-8 max-sm:col-span-1">
                <div class="bg-white overflow-hidden">
                    <div class="relative">
                        <div id="mapa" class="w-[819px] max-sm:w-full h-[705px] max-sm:h-[350px]"></div>
                        <!-- Loading overlay -->
                        <div x-show="cargando"
                            class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center">
                            <div class="flex items-center space-x-2">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                                <span class="text-gray-600 max-sm:text-[14px]">Cargando...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function mapaComponent() {
            return {
                mapa: null,
                marcadores: [],
                puntosVenta: @json($puntosVenta),
                puntosVentaOriginales: @json($puntosVenta),
                puntosVentaFiltrados: @json($puntosVenta),
                puntoSeleccionado: null,
                cargando: false,
                filtros: {
                    nombre: '',
                    provincia: '{{ $provincia }}',
                    localidad: '{{ $localidad }}'
                },
                localidades: @json($localidades),
                nombresUnicos: [],

                init() {
                    this.inicializarMapa();
                    this.cargarMarcadores();
                    this.generarNombresUnicos();
                    this.aplicarFiltrosIniciales();
                },

                generarNombresUnicos() {
                    const nombres = [...new Set(this.puntosVentaOriginales.map(punto => punto.nombre))];
                    this.nombresUnicos = nombres.sort();
                },

                aplicarFiltrosIniciales() {
                    this.filtrar();
                },

                inicializarMapa() {
                    this.mapa = L.map('mapa').setView([-34.6118, -58.3960], 6);

                    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://www.carto.com/">CARTO</a>',
                        subdomains: 'abcd',
                        maxZoom: 20
                    }).addTo(this.mapa);
                },

                cargarMarcadores() {
                    this.marcadores.forEach(marcador => {
                        this.mapa.removeLayer(marcador);
                    });
                    this.marcadores = [];

                    // Ícono clásico de Leaflet, recoloreado a #0049A0
                    const customIcon = L.divIcon({
                        className: "custom-pin",
                        html: `
   <svg xmlns="http://www.w3.org/2000/svg" width="38" height="49" viewBox="0 0 38 49" fill="none">
  <g filter="url(#filter0_d_26_2467)">
    <path d="M19.3373 3.80273C17.0836 3.80334 14.8709 4.40558 12.9278 5.54726C10.9847 6.68895 9.3816 8.32867 8.2841 10.2971C7.1866 12.2655 6.63449 14.4912 6.6848 16.7443C6.7351 18.9974 7.38603 21.1963 8.57029 23.1137L18.6163 39.3037C18.7111 39.457 18.8436 39.5834 19.0011 39.671C19.1587 39.7585 19.336 39.8042 19.5163 39.8037H19.5253C19.7068 39.803 19.885 39.7553 20.0426 39.6654C20.2003 39.5756 20.3321 39.4465 20.4252 39.2907L30.2153 22.9437C31.3604 21.0224 31.976 18.8319 31.9994 16.5953C32.0228 14.3586 31.4532 12.1557 30.3486 10.2108C29.2439 8.26588 27.6436 6.64837 25.7106 5.52293C23.7776 4.39748 21.581 3.80429 19.3443 3.80373L19.3373 3.80273Z" fill="#0049A0"/>
  </g>
  <path d="M19.3359 21.7998C23.2019 21.7998 26.3359 18.6658 26.3359 14.7998C26.3359 10.9338 23.2019 7.7998 19.3359 7.7998C15.4699 7.7998 12.3359 10.9338 12.3359 14.7998C12.3359 18.6658 15.4699 21.7998 19.3359 21.7998Z" fill="white"/>
  <defs>
    <filter id="filter0_d_26_2467" x="0.681641" y="0.802734" width="37.3184" height="48.001" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
      <feFlood flood-opacity="0" result="BackgroundImageFix"/>
      <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
      <feOffset dy="3"/>
      <feGaussianBlur stdDeviation="3"/>
      <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.161 0"/>
      <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_26_2467"/>
      <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_26_2467" result="shape"/>
    </filter>
  </defs>
</svg>
    `,
                        iconSize: [32, 32],
                        iconAnchor: [16, 32],
                        popupAnchor: [0, -32],
                    });


                    this.puntosVentaFiltrados.forEach(punto => {
                        const marcador = L.marker([punto.latitud, punto.longitud], {
                                icon: customIcon
                            })
                            .bindPopup(this.crearPopup(punto))
                            .addTo(this.mapa);


                        marcador.on('click', () => {
                            this.seleccionarPunto(punto);
                        });

                        this.marcadores.push(marcador);
                    });

                    if (this.marcadores.length > 0) {
                        const grupo = new L.featureGroup(this.marcadores);
                        this.mapa.fitBounds(grupo.getBounds().pad(0.1));
                    }
                },

                crearPopup(punto) {
                    return `
                        <div class="p-2">
                            <h3 class="font-semibold text-gray-900 mb-2">${punto.nombre}</h3>
                            <p class="text-sm text-gray-600 mb-1">${punto.direccion}</p>
                            <p class="text-sm text-gray-500 mb-2">${punto.localidad}, ${punto.provincia}</p>
                            ${punto.telefono ? `<div class="flex items-center text-sm text-gray-600 mb-1">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        ${punto.telefono}
                                    </div>` : ''}
                            ${punto.email ? `<div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        ${punto.email}
                                    </div>` : ''}
                        </div>
                    `;
                },

                seleccionarPunto(punto) {
                    this.puntoSeleccionado = punto;
                    this.mapa.setView([punto.latitud, punto.longitud], 15);

                    this.marcadores.forEach(marcador => {
                        if (marcador.getLatLng().lat == punto.latitud &&
                            marcador.getLatLng().lng == punto.longitud) {
                            marcador.openPopup();
                        }
                    });
                },

                async filtrarPorProvincia() {
                    if (this.filtros.provincia) {
                        try {
                            this.cargando = true;
                            const response = await fetch(
                                `/donde-comprar/localidades?provincia=${encodeURIComponent(this.filtros.provincia)}`
                                );
                            const localidadesAPI = await response.json();
                            const localidadesPuntos = [...new Set(
                                this.puntosVentaOriginales
                                .filter(punto => punto.provincia === this.filtros.provincia)
                                .map(punto => punto.localidad)
                                .filter(localidad => localidad)
                            )].sort();

                            const todasLocalidades = [...new Set([
                                ...localidadesPuntos,
                                ...localidadesAPI.map(loc => loc.name || loc)
                            ])].sort();

                            this.localidades = todasLocalidades.map(nombre => ({
                                name: nombre
                            }));
                        } catch (error) {
                            console.error('Error al cargar localidades:', error);
                            const localidadesPuntos = [...new Set(
                                this.puntosVentaOriginales
                                .filter(punto => punto.provincia === this.filtros.provincia)
                                .map(punto => punto.localidad)
                                .filter(localidad => localidad)
                            )].sort();
                            this.localidades = localidadesPuntos.map(nombre => ({
                                name: nombre
                            }));
                        } finally {
                            this.cargando = false;
                        }
                    } else {
                        this.localidades = [];
                    }
                    this.filtros.localidad = '';
                    this.filtrar();
                },

                filtrar() {
                    this.puntosVentaFiltrados = this.puntosVentaOriginales.filter(punto => {
                        if (this.filtros.nombre && punto.nombre !== this.filtros.nombre) return false;
                        if (this.filtros.provincia && punto.provincia !== this.filtros.provincia) return false;
                        if (this.filtros.localidad && punto.localidad !== this.filtros.localidad) return false;
                        return true;
                    });

                    if (this.puntoSeleccionado && !this.puntosVentaFiltrados.find(p => p.id === this.puntoSeleccionado
                        .id)) {
                        this.puntoSeleccionado = null;
                    }

                    this.cargarMarcadores();
                }
            }
        }
    </script>

@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endpush
