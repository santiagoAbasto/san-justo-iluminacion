@extends('layouts.default')

@section('title', 'Novedades - SR33')

@section('description', $metadatos->description ?? "")
@section('keywords', $metadatos->keywords ?? "")

@section('content')

    <div class="max-w-[1200px] mx-auto my-20 max-sm:my-10">
        <div class="grid grid-cols-3 gap-6 max-sm:grid-cols-1 max-sm:px-4">
            @foreach($novedades as $novedad)
                <a href="{{ url('/novedades/' . $novedad->id) }}"
                    class="flex flex-col gap-2 max-w-[392px] h-[560px] border rounded-sm transition transform hover:-translate-y-1 hover:shadow-lg duration-300">
                    <div class="max-w-[391px] min-h-[321px] rounded-t-sm">
                        <img src="{{ $novedad->image }}" alt="{{ $novedad->title }}"
                            class="h-full w-full object-cover rounded-t-sm">
                    </div>
                    <div class="flex h-full flex-col justify-between p-4">
                        <div class="flex flex-col gap-2">
                            <p class="text-primary-orange text-sm font-bold uppercase">{{ $novedad->type }}</p>
                            <div>
                                <p class="text-xl font-bold sm:text-2xl">{{ $novedad->title }}</p>
                                <div class="line-clamp-3 overflow-hidden break-words">
                                    {!! $novedad->text !!}
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-row items-center justify-between">
                            <p class="font-bold text-[#B2B2B2]">Leer más</p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="#B2B2B2" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

@endsection