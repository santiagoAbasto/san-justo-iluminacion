<!-- resources/views/components/novedades-inicio.blade.php -->

<div class="w-full py-10 sm:py-16 md:py-20 max-sm:py-6">

    <div class="mx-auto flex max-w-[1200px] max-sm:max-w-full max-sm:px-4 flex-col gap-6 sm:gap-8 max-sm:gap-4">
        <div class="flex flex-row gap-4 sm:flex-row sm:items-center justify-between sm:gap-0 max-sm:gap-3">
            <h2 class="text-2xl font-bold sm:text-2xl md:text-3xl max-sm:text-xl">Novedades</h2>
            <a href="{{ url('/novedades') }}"
                class="text-primary-orange border-primary-orange hover:bg-primary-orange flex h-[41px] max-sm:h-[36px] w-[127px] max-sm:w-[100px] items-center justify-center border text-base max-sm:text-sm font-semibold transition duration-300 hover:text-white rounded-sm">
                Ver todas
            </a>
        </div>
        <div class="flex flex-row max-sm:flex-col gap-6 max-sm:gap-4">
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
</div>