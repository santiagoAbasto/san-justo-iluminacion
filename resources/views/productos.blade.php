@extends('layouts.default')
@section('title', 'Productos - San Justo Iluminacion')

@section('description', $metadatos->description ?? "")
@section('keywords', $metadatos->keywords ?? "")

@section('content')
    <!-- Breadcrumb responsive -->
    <div
        class="absolute top-30 left-1/2 w-[1224px] max-w-[1224px] max-xl:w-full max-xl:px-6 max-lg:px-4 max-md:px-3 max-sm:px-3 -translate-x-1/2 flex flex-row gap-1 z-100">
        <a href="/"
            class="text-black font-medium text-[12px] max-sm:text-[11px] hover:text-primary-orange transition-colors">{{__('Inicio')}}</a>
        <span class="text-black font-medium text-[12px] max-sm:text-[11px]">/</span>
        <span class="text-black font-medium text-[12px] max-sm:text-[11px]">{{__('Productos')}}</span>
    </div>

    <div class="flex flex-col gap-10 max-lg:gap-8 max-md:gap-6 max-sm:gap-6 py-20 max-lg:py-16 max-md:py-14 max-sm:py-12">

        <!-- Search bar component -->
        <x-search-bar :espacios="$espacios" :lineas="$lineas" :usos="$usos" :ambientes="$ambientes" :espacio="$espacio"
            :linea="$linea" :ambiente="$ambiente" :uso="$uso" :code="$code" />

        <!-- Main content with sidebar and products -->
        <div
            class="flex flex-col lg:flex-row gap-6 max-sm:gap-4 w-[1224px] max-w-[1224px] max-xl:w-full max-xl:px-6 max-lg:px-4 max-md:px-3 max-sm:w-full max-sm:px-4 mx-auto">

            {{-- Sidebar with categories (si existe) --}}
            {{-- Esta sección parece estar comentada o no implementada en el original --}}

            <!-- Products grid section -->
            <div class="w-full mb-10 max-lg:mb-8 max-md:mb-6 max-sm:mb-4">
                <div
                    class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-4 max-lg:grid-cols-3 max-md:grid-cols-2 max-sm:grid-cols-1 gap-6 max-lg:gap-5 max-md:gap-4 max-sm:gap-4">
                    @forelse($productos as $producto)
                        <a href="{{ "/productos/" . $producto->code }}"
                            class="min-h-[332px] max-lg:min-h-[300px] max-md:min-h-[280px] max-sm:min-h-[250px] flex flex-col w-full max-w-[288px] max-lg:max-w-full mx-auto max-sm:mx-0 rounded-sm border-[#DEDFE0] hover:shadow-md transition-shadow duration-300 bg-white overflow-hidden group">
                            <div class="h-full flex flex-col">
                                @if ($producto->imagenes->count() > 0)
                                    <div
                                        class="relative min-h-[287px] max-lg:min-h-[250px] max-md:min-h-[220px] max-sm:h-[200px] overflow-hidden">
                                        <img src="{{ $producto->imagenes->first()->image}}" alt="{{ $producto->name }}"
                                            class="w-full h-full object-cover rounded-t-sm group-hover:scale-105 transition-transform duration-300"
                                            onerror="this.onerror=null; this.src='{{$logos->logo_secundario}}'; this.classList.remove('object-cover'); this.classList.add('object-contain', 'p-4', 'bg-gray-50');">
                                        <h2
                                            class="absolute left-3 bottom-2 text-[14px] max-md:text-[13px] max-sm:text-[12px] font-semibold uppercase text-primary-orange bg-white/90 px-2 py-1 rounded max-sm:left-2 max-sm:bottom-1">
                                            {{$producto->categoria->name ?? ''}}
                                        </h2>
                                    </div>
                                @else
                                    <div
                                        class="relative min-h-[287px] max-lg:min-h-[250px] max-md:min-h-[220px] max-sm:h-[200px] bg-gray-50 flex items-center justify-center overflow-hidden">
                                        <img src="{{$logos->logo_principal}}" alt="{{ $producto->name }}"
                                            class="w-full h-full object-contain rounded-t-sm p-4 max-sm:p-3 group-hover:scale-105 transition-transform duration-300">
                                        <h2
                                            class="absolute left-3 bottom-2 text-[14px] max-md:text-[13px] max-sm:text-[12px] font-semibold uppercase text-primary-orange bg-white/90 px-2 py-1 rounded max-sm:left-2 max-sm:bottom-1">
                                            {{$producto->categoria->name ?? ''}}
                                        </h2>
                                    </div>
                                @endif

                                <div
                                    class="flex flex-col justify-start h-full px-3 max-sm:px-2 mt-3 max-sm:mt-2 pb-3 max-sm:pb-2">
                                    <h2
                                        class="text-[16px] max-md:text-[15px] max-sm:text-[14px] font-semibold text-primary-orange mb-1">
                                        {{$producto->code}}
                                    </h2>
                                    <p
                                        class="overflow-hidden line-clamp-2 text-[20px] max-lg:text-[18px] max-md:text-[16px] max-sm:text-[15px] font-custom! leading-tight text-gray-800 group-hover:text-black transition-colors flex-1">
                                        {{$producto->name}}
                                    </p>

                                    <!-- Indicador visual para hover en desktop -->
                                    <div
                                        class="mt-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 max-sm:opacity-100">
                                        <span class="text-[12px] text-primary-orange font-medium">
                                            {{__('Ver detalles')}} →
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full py-12 max-lg:py-10 max-md:py-8 max-sm:py-6 text-center">
                            <div class="max-w-md mx-auto">
                                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2M4 13h2m0 0V9a2 2 0 012-2h2m-2 2v4">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">{{__('No hay productos disponibles')}}</h3>
                                <p class="text-gray-500 text-sm max-sm:text-xs">
                                    {{__('No se encontraron productos en esta categoría. Prueba con otros filtros.')}}
                                </p>
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- Enlaces de paginación responsivos --}}
                @if($productos->hasPages())
                    <div class="mt-8 max-lg:mt-6 max-md:mt-5 max-sm:mt-4 flex flex-col justify-center">
                        <div class="pagination-wrapper">
                            {{ $productos->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Estilos adicionales para paginación responsive -->
    <style>
        .pagination-wrapper .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .pagination-wrapper .pagination li {
            list-style: none;
        }

        .pagination-wrapper .pagination li a,
        .pagination-wrapper .pagination li span {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            border: 1px solid #e5e5e5;
            border-radius: 4px;
            text-decoration: none;
            color: #374151;
            font-size: 14px;
            transition: all 0.2s;
        }

        .pagination-wrapper .pagination li a:hover {
            background-color: #f9fafb;
            border-color: #d1d5db;
        }

        .pagination-wrapper .pagination li.active span {
            background-color: #f97316;
            border-color: #f97316;
            color: white;
        }

        .pagination-wrapper .pagination li.disabled span {
            color: #9ca3af;
            cursor: not-allowed;
        }

        /* Responsive pagination */
        @media (max-width: 640px) {

            .pagination-wrapper .pagination li a,
            .pagination-wrapper .pagination li span {
                min-width: 36px;
                height: 36px;
                font-size: 13px;
                padding: 0 8px;
            }

            .pagination-wrapper .pagination {
                gap: 0.25rem;
            }

            /* Hide some pagination elements on mobile */
            .pagination-wrapper .pagination li:not(.active):not(:first-child):not(:last-child):not(:nth-child(2)):not(:nth-last-child(2)) {
                display: none;
            }
        }
    </style>
@endsection