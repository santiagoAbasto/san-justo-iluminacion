@extends('layouts.default')
@section('title', $producto->name . ' ' . $producto->code . ' | San Justo Iluminación')

@section('description', \Illuminate\Support\Str::limit(strip_tags($producto->desc_visible ?: $producto->name), 155))
@section('seo_image', $producto->imagenes->first()->image ?? '')
@section('canonical', route('producto.show', $producto->code))

@push('head')
    <script type="application/ld+json">
        {!! json_encode(array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $producto->name,
            'sku' => $producto->code,
            'description' => \Illuminate\Support\Str::limit(strip_tags($producto->desc_visible ?: $producto->name), 500),
            'image' => $producto->imagenes->pluck('image')->values()->all(),
            'brand' => [
                '@type' => 'Brand',
                'name' => 'San Justo Iluminación',
            ],
            'url' => url('/productos/' . $producto->code),
        ]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush


@section('content')

    <div class="flex flex-col gap-10 max-sm:gap-6">
        <!-- Breadcrumb navigation -->
        <div class="absolute top-30 max-sm:top-5 left-1/2 w-[1224px] max-sm:w-full max-sm:px-4 -translate-x-1/2 flex flex-row gap-1 z-100">
            <a href="/" class="text-black font-medium text-[12px]">{{__('Inicio')}}</a>
            <span class="text-black font-medium text-[12px]">/</span>
            <a href="/productos" class="text-black font-medium text-[12px]">{{__('Productos')}}</a>
            <span class="text-black font-medium text-[12px]">/</span>
            <a href="{{"/productos/" . $producto->code}}"
                class="text-black font-medium text-[12px]">{{$producto->name ?? ""}}</a>
        </div>


        <!-- Main content with sidebar and product detail -->
        <div class="flex flex-col lg:flex-row gap-6 w-[1224px] mx-auto max-sm:w-full max-sm:px-4 max-sm:gap-4 py-20 max-sm:py-8">
            <!-- Sidebar (1/4 width) -->


            <!-- Product Detail (3/4 width) -->
            <div class="w-full max-sm:w-full">
                <div class="flex flex-col md:flex-row gap-5 max-sm:gap-4">
                    <!-- Image Gallery -->
                    <div class="relative w-full flex flex-col gap-5 max-sm:mt-10">

                        <!-- Main Image -->
                        <div class="flex items-center w-full justify-center h-[544px] max-sm:h-[300px] border rounded-sm">
                            @if ($producto->imagenes->first())
                                <img id="mainImage" class="rounded-sm w-full h-full object-cover" src="{{ $producto->imagenes->first()->image }}"
                                    alt="{{ $producto->name }}" 
                                    class="w-full h-full object-cover object-center transition-opacity duration-300 ease-in-out" onerror="this.onerror=null; this.src='{{$logos->logo_secundario}}'; this.classList.remove('object-cover'); this.classList.add('object-contain', 'p-4', 'bg-gray-50');">
                            @else
                                <div
                                    class="w-full h-full bg-gray-100 text-gray-400 flex items-center justify-center transition-opacity duration-300 ease-in-out">
                                    <span class="text-sm max-sm:text-xs">Sin imagen disponible</span>
                                </div>
                            @endif
                        </div>
                        <div
                            class="gap-2 flex flex-row absolute -bottom-24 max-sm:static max-sm:mt-4 max-sm:justify-start max-sm:gap-1.5 max-sm:order-2">
                            @foreach ($producto->imagenes as $imagen)
                                <div class="border border-gray-200 w-[78px] h-[78px] cursor-pointer hover:border-main-color rounded-sm max-sm:w-[60px] max-sm:h-[60px]
                                            {{ $loop->first ? 'border-main-color' : '' }}"
                                    onclick="changeMainImage('{{ $imagen->image }}', this)">
                                    <img src="{{ $imagen->image }}" onerror="this.onerror=null; this.src='{{$logos->logo_secundario}}'; this.classList.remove('object-cover'); this.classList.add('object-contain', 'p-4', 'bg-gray-50');" alt="Thumbnail"
                                        class="w-full h-full object-cover rounded-sm">
                                </div>
                            @endforeach
                        </div>

                        <!-- Thumbnails -->

                    </div>

                    <!-- Product Info -->
                    <div class="w-full flex flex-col min-h-full justify-between max-sm:w-full max-sm:mt-6">
                        <div class="flex flex-col gap-10 max-sm:gap-6">
                            <div class="flex flex-col">
                                <h2 class="text-[24px] max-sm:text-[18px] leading-tight">{{ $producto->code }}</h2>
                                <h2 class="font-semibold text-[32px] max-sm:text-[24px] leading-tight">{{ $producto->name }}</h2>
                            </div>

                            <div class="flex flex-col gap-2">

                                @if ($producto->lampara)
                                    <div class="flex flex-row text-[16px] max-sm:text-[14px] justify-between border-b pb-2">
                                        <p class="">{{__("Lampara")}}</p>
                                        <p class="">{{$producto->lampara}}</p>
                                    </div>
                                @endif

                                @if ($producto->medidas)
                                    <div class="flex flex-row text-[16px] max-sm:text-[14px] justify-between border-b pb-2">
                                        <p class="">{{__("Medidas")}}</p>
                                        <p class="">{{$producto->medidas}}</p>
                                    </div>
                                @endif

                                @if ($producto->colores->count() > 0)
                                    <div class="flex flex-row text-[16px] max-sm:text-[14px] justify-between border-b pb-2">
                                        <p class="">{{__("Colores")}}</p>

                                        <div class="flex flex-row items-end gap-2" aria-label="Colores disponibles">
                                            @foreach ($producto->colores as $color)
                                                <span class="group/color relative inline-flex hover:z-10 focus-within:z-10">
                                                    <span
                                                        tabindex="0"
                                                        role="img"
                                                        aria-label="Color: {{ $color->name }}"
                                                        title="{{ $color->name }}"
                                                        class="h-[28px] w-[28px] cursor-help rounded-sm border border-black/15 shadow-sm outline-none transition-[transform,box-shadow,outline-color] duration-200 ease-[cubic-bezier(0.22,1,0.36,1)] hover:-translate-y-3 hover:scale-125 hover:shadow-md hover:ring-2 hover:ring-primary-orange/55 hover:ring-offset-2 focus:-translate-y-3 focus:scale-125 focus:shadow-md focus:ring-2 focus:ring-primary-orange/55 focus:ring-offset-2 max-sm:h-[24px] max-sm:w-[24px]"
                                                        style="background-color: {{ $color->hex }}"
                                                    ></span>
                                                    <span role="tooltip" class="pointer-events-none absolute bottom-full left-1/2 z-20 mb-3 -translate-x-1/2 translate-y-1 whitespace-nowrap rounded-sm bg-[#101828] px-2.5 py-1.5 text-xs font-medium text-white opacity-0 shadow-md transition duration-200 ease-[cubic-bezier(0.22,1,0.36,1)] group-hover/color:translate-y-0 group-hover/color:opacity-100 group-focus-within/color:translate-y-0 group-focus-within/color:opacity-100 motion-reduce:transition-none">
                                                        {{ $color->name }}
                                                    </span>
                                                </span>
                                            @endforeach

                                        </div>
                                    </div>
                                @endif
                                @if ($producto->origen)
                                    <p class="max-sm:text-[14px]">{{$producto->origen}}</p>
                                @endif

                                @if ($producto->certificado)
                                    <a href="{{asset('storage/' . $producto->certificado)}}" target="_blank"
                                        download="Certificado de seguridad electrica" class="underline text-[16px] max-sm:text-[14px]">
                                        {{__("Certificado de seguridad eléctrica")}}
                                    </a>
                                @endif
                            </div>

                        </div>
                        <div class="flex flex-row max-sm:flex-col gap-5 max-sm:gap-3 max-sm:mt-6">
                            <a href="{{ route('contacto', ['mensaje' => $producto->name]) }}"
                                class="w-full flex justify-center rounded-sm items-center bg-primary-orange text-white font-bold h-[41px] max-sm:h-[36px] max-sm:text-sm">
                                {{__("Consultar")}}
                            </a>
                            @if ($producto->instructivo)
                                <a href="{{ asset('storage/' . $producto->instructivo) }}" target="_blank"
                                    download="Instructivo_{{$producto->code}}"
                                    class="w-full flex justify-center rounded-sm items-center border border-primary-orange text-primary-orange font-bold h-[41px] max-sm:h-[36px] max-sm:text-sm">
                                    {{__("Instructivo")}}
                                </a>
                            @endif

                        </div>

                    </div>
                </div>


                <div class="pt-40 max-sm:pt-20 flex flex-row max-sm:flex-col gap-5 max-sm:gap-4">
                    <div class="flex flex-col gap-3 max-sm:gap-2 w-full">
                        <h2 class="font-semibold text-[32px] max-sm:text-[24px]">
                            {{request('lang') == 'en' ? $producto->linea->name_en : $producto->linea->name_es}}
                        </h2>
                        <div class="text-[20px] max-sm:text-[16px] leading-tight">
                            {!! request('lang') == 'en' ? $producto->linea->text_en : $producto->linea->text_es !!}
                        </div>
                    </div>

                    <div class="w-full h-[651px] max-sm:h-[300px]">
                        @if ($producto->linea->image)
                            <img src="{{ asset("storage/" . $producto->linea->image) }}" onerror="this.onerror=null; this.src='{{$logos->logo_secundario}}'; this.classList.remove('object-cover'); this.classList.add('object-contain', 'p-4', 'bg-gray-50');" alt="{{ $producto->linea->name }}"
                                class="w-full h-full object-cover rounded-tr-[70px] rounded-bl-[70px] max-sm:rounded-tr-[30px] max-sm:rounded-bl-[30px]">
                        @else
                            <div class="w-full h-full flex items-center justify-center rounded-tr-[70px] rounded-bl-[70px] max-sm:rounded-tr-[30px] max-sm:rounded-bl-[30px] bg-gray-100 text-gray-500">
                                Sin imagen
                            </div>
                        @endif
                    </div>
                </div>



                <!-- Productos relacionados -->
                <div class="py-20 pt-30 max-sm:py-10 max-sm:pt-15">
                    <h2 class="text-[28px] font-bold mb-8 max-sm:text-xl max-sm:mb-6">{{__("Productos relacionados")}}</h2>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 max-sm:grid-cols-1 max-sm:gap-4">
                        @forelse($productosRelacionados as $prodRelacionado)
                            <a href="{{ "/productos/" . $prodRelacionado->code }}"
                            class="min-h-[332px] max-lg:min-h-[300px] max-md:min-h-[280px] max-sm:min-h-[250px] flex flex-col w-full max-w-[288px] max-lg:max-w-full mx-auto max-sm:mx-0 rounded-sm border-[#DEDFE0] hover:shadow-md transition-shadow duration-300 bg-white overflow-hidden group">
                            <div class="h-full flex flex-col">
                                @if ($prodRelacionado->imagenes->count() > 0)
                                    <div class="relative min-h-[287px] max-lg:min-h-[250px] max-md:min-h-[220px] max-sm:h-[200px] overflow-hidden">
                                        <img src="{{ $prodRelacionado->imagenes->first()->image}}" onerror="this.onerror=null; this.src='{{$logos->logo_secundario}}'; this.classList.remove('object-cover'); this.classList.add('object-contain', 'p-4', 'bg-gray-50');" alt="{{ $prodRelacionado->name }}"
                                            class="w-full h-full object-cover rounded-t-sm group-hover:scale-105 transition-transform duration-300">
                                        <h2 class="absolute left-3 bottom-2 text-[14px] max-md:text-[13px] max-sm:text-[12px] font-semibold uppercase text-primary-orange bg-white/90 px-2 py-1 rounded max-sm:left-2 max-sm:bottom-1">
                                            {{$prodRelacionado->categoria->name ?? ''}}
                                        </h2>
                                    </div>
                                @else
                                    <div class="relative min-h-[287px] max-lg:min-h-[250px] max-md:min-h-[220px] max-sm:h-[200px] bg-gray-50 flex items-center justify-center overflow-hidden">
                                        <img src="{{$logos->logo_principal}}" alt="{{ $prodRelacionado->name }}" onerror="this.onerror=null; this.src='{{$logos->logo_secundario}}'; this.classList.remove('object-cover'); this.classList.add('object-contain', 'p-4', 'bg-gray-50');"
                                            class="w-full h-full object-contain rounded-t-sm p-4 max-sm:p-3 group-hover:scale-105 transition-transform duration-300">
                                        <h2 class="absolute left-3 bottom-2 text-[14px] max-md:text-[13px] max-sm:text-[12px] font-semibold uppercase text-primary-orange bg-white/90 px-2 py-1 rounded max-sm:left-2 max-sm:bottom-1">
                                            {{$prodRelacionado->categoria->name ?? ''}}
                                        </h2>
                                    </div>
                                @endif

                                <div class="flex flex-col justify-start h-full px-3 max-sm:px-2 mt-3 max-sm:mt-2 pb-3 max-sm:pb-2">
                                    <h2 class="text-[16px] max-md:text-[15px] max-sm:text-[14px] font-semibold text-primary-orange mb-1">
                                        {{$prodRelacionado->code}}
                                    </h2>
                                    <p class="overflow-hidden line-clamp-2 text-[20px] max-lg:text-[18px] max-md:text-[16px] max-sm:text-[15px] font-custom! leading-tight text-gray-800 group-hover:text-black transition-colors flex-1">
                                        {{$prodRelacionado->name}}
                                    </p>
                                    
                                    <!-- Indicador visual para hover en desktop -->
                                    <div class="mt-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 max-sm:opacity-100">
                                        <span class="text-[12px] text-primary-orange font-medium">
                                            {{__('Ver detalles')}} →
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @empty
                            <div class="col-span-4 py-8 text-center text-gray-500 max-sm:col-span-1 max-sm:py-6">
                                No hay productos relacionados disponibles.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function changeMainImage(src, thumbnail) {
            const mainImage = document.getElementById('mainImage');

            // Fade out effect
            mainImage.style.opacity = '0';

            // Change image after fade out completes
            setTimeout(() => {
                mainImage.src = src;

                // Fade in the new image
                mainImage.style.opacity = '1';

                // Update thumbnail borders
                document.querySelectorAll('.flex.gap-2 > div').forEach(thumb => {
                    thumb.classList.remove('border-main-color');
                });
                thumbnail.classList.add('border-main-color');
            }, 300);
        }

        // Ensure image is visible on initial load
        document.addEventListener('DOMContentLoaded', () => {
            const mainImage = document.getElementById('mainImage');
            mainImage.style.opacity = '1';
        });
    </script>

    <style>
        #mainImage {
            opacity: 0;
        }
    </style>
@endsection
