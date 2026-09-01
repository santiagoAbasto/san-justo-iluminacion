@extends('layouts.default')
@section('title', 'Nuestra empresa | San Justo Iluminación')
@section('description', $metadatos->description ?? "")
@section('keywords', $metadatos->keywords ?? "")

@section('content')
    <div class="relative w-full h-[559px] max-sm:h-[300px]">
        <div
            class="absolute top-5 left-1/2 w-[1224px] max-sm:w-full max-sm:px-4 -translate-x-1/2 flex flex-row gap-1 z-100">
            <a href="/" class="text-white font-medium text-[12px]">{{('Inicio')}}</a>
            <span class="text-white font-medium text-[12px]">/</span>
            <span class="text-white font-medium text-[12px]">{{('Nosotros')}}</span>
        </div>
        <div class="w-full h-full">
            @php
                $ext = pathinfo($banner->media, PATHINFO_EXTENSION);
            @endphp
            @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                <img src="{{ asset($banner->media) }}" alt="Slider Image" class="w-full h-full object-cover"
                    data-duration="6000">
            @elseif (in_array($ext, ['mp4', 'webm', 'ogg']))
                <video class="w-full h-full object-cover object-center" autoplay loop muted onended="nextSlide()">
                    <source src="{{ asset($banner->media) }}" type="video/{{ $ext }}">
                    {{ __('Tu navegador no soporta el formato de video.') }}
                </video>
            @endif
        </div>
    </div>

    <div class="w-[1224px] max-sm:w-full max-sm:px-4 mx-auto py-15 max-sm:py-8 flex flex-col gap-10 max-sm:gap-6">
        @foreach ($secciones as $seccion)
            <div class="flex flex-row max-sm:flex-col {{ $loop->even ? 'flex-row-reverse max-sm:flex-col' : '' }}">
                <div
                    class="w-full {{ $loop->even ? 'rounded-tr-[70px] rounded-bl-[70px] max-sm:rounded-tl-[0px] max-sm:rounded-br-[0px]' : 'rounded-tl-[70px] rounded-br-[70px] max-sm:rounded-tr-[0px] max-sm:rounded-bl-[0px]' }} h-[440px] max-sm:h-[250px] overflow-hidden">
                    <img src="{{$seccion->image}}" class="w-full h-full object-cover" alt="">
                </div>
                <div class="w-full flex flex-col px-10 max-sm:px-0 max-sm:pt-6 py-4 max-sm:py-2 gap-5 max-sm:gap-3">
                    <h2
                        class="text-[32px] max-sm:text-[24px] font-semibold font-custom! leading-tight max-w-[560px] max-sm:max-w-full">
                        {{request('lang') == 'en' ? $seccion->name_en : $seccion->name_es}}
                    </h2>
                    <div class="text-[20px]! max-sm:text-[16px]! max-w-[560px]! max-sm:max-w-full! break-words">
                        {!! request('lang') == 'en' ? $seccion->text_en : $seccion->text_es !!}
                    </div>
                </div>
            </div>
        @endforeach

        <div class="flex flex-col gap-5 max-sm:gap-4">
            <h2 class="font-semibold text-[32px] max-sm:text-[24px] font-custom!">{{__('Cómo hacemos las cosas')}}</h2> 
            <div class="grid grid-cols-4 max-sm:grid-cols-1 gap-5 max-sm:gap-4">
                @foreach ($tarjetas as $tarjeta)
                    <div
                        class="flex flex-col h-[278px] max-sm:h-auto max-sm:min-h-[200px] pt-6 max-sm:pt-4 border rounded-sm items-center px-4 max-sm:px-3">
                        <div class="h-[66px] max-sm:h-[50px]">
                            <img class="w-full h-full object-cover" src="{{ $tarjeta->image }}" alt="">
                        </div>
                        <h2 class="text-[20px] max-sm:text-[18px] text-center font-semibold font-custom! py-4 max-sm:py-3">
                            {{request('lang') == 'en' ? $tarjeta->name_en : $tarjeta->name_es}}
                        </h2>
                        <div class="text-[16px]! max-sm:text-[14px]! text-center! break-words w-full">
                            {!! request('lang') == 'en' ? $tarjeta->text_en : $tarjeta->text_es !!}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
