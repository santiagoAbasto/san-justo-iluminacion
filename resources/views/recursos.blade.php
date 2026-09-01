@extends('layouts.default')

@section('title', 'Recursos - San Justo Iluminación')

@section('description', $metadatos->description ?? "")
@section('keywords', $metadatos->keywords ?? "")

@section('content')
    <div class="absolute top-30 left-1/2 w-[1224px] -translate-x-1/2 flex flex-row gap-1 z-100">
        <a href="/" class="text-black font-medium text-[12px]">{{__('Inicio')}}</a>
        <span class="text-black font-medium text-[12px]">/</span>
        <span class="text-black font-medium text-[12px]">{{__('Recursos')}}</span>
    </div>
    <div class="mx-auto flex w-full max-w-[1224px] flex-col min-h-[80vh]  px-4 py-10  md:px-0 md:py-20">
        <div class="flex flex-col mb-10 max-w-[740px]">
            <h2 class="text-[32px] font-semibold font-custom!">
                {{request('lang') == 'en' ? $recursos->title_en : $recursos->title_es}}
            </h2>
            <p class="text-[20px]! max-w-[903px]">
                {!! request('lang') == 'en' ? $recursos->text_en : $recursos->text_es !!}
            </p>
        </div>

        <div class="flex flex-row gap-5 flex-wrap">
            <a href="{{asset('storage/' . $recursos->archivo_fotos)}}" download="FOTOS"
                class="w-[184px] h-[184px] bg-[#F2EEED] flex items-center rounded-sm flex-col justify-between py-6">
                <div class="w-[86px] h-[76px]">
                    <img class="w-full h-full object-contain" src="" alt="">
                </div>
                <p class="text-[16px]">{{__("FOTOS")}}</p>
            </a>
            <a href="{{asset('storage/' . $recursos->archivo_cad)}}" download="CAD"
                class="w-[184px] h-[184px] bg-[#F2EEED] flex items-center rounded-sm flex-col justify-between py-6">
                <div class="w-[86px] h-[76px]">
                    <img class="w-full h-full object-contain" src="" alt="">
                </div>
                <p class="text-[16px]">{{__("CAD")}}</p>
            </a>
        </div>


    </div>
@endsection