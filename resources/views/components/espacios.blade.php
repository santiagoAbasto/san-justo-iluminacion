<div
    class="flex flex-col w-[1224px] max-w-[1224px] max-xl:w-full max-xl:px-6 max-lg:px-4 max-sm:px-3 mx-auto mt-10 max-lg:mt-8 max-md:mt-6 max-sm:mt-4">
    {{-- Buscar el título con la sección "espacios" --}}
    <h2
        class="text-[32px] max-lg:text-[28px] max-md:text-[24px] max-sm:text-[20px] font-semibold font-custom! mb-6 max-lg:mb-5 max-md:mb-4 max-sm:mb-3">
        {{request('lang') == 'en' ? $titulo->title_en : $titulo->title_es  }}
    </h2>
    <div class="flex flex-row flex-wrap max-lg:flex-col w-full gap-6 max-lg:gap-5 max-md:gap-4 max-sm:gap-3">
        @foreach ($espaciosHome as $espacio)
            <div x-data="{ isHovered: false }" @mouseenter="isHovered = true" @mouseleave="isHovered = false"
                style="background-image: url('{{ asset("storage/" . $espacio->image) }}');"
                class="w-[600px] max-lg:w-full h-[390px] max-lg:h-[350px] max-md:h-[300px] max-sm:h-[250px] rounded-bl-[70px] max-lg:rounded-bl-[50px] max-md:rounded-bl-[40px] max-sm:rounded-bl-[30px] rounded-tr-[70px] max-lg:rounded-tr-[50px] max-md:rounded-tr-[40px] max-sm:rounded-tr-[30px] overflow-hidden relative bg-no-repeat bg-center bg-cover">
                <div class="absolute -left-[153px] max-xl:-left-[130px] max-lg:-left-[120px] max-md:-left-[100px] max-sm:-left-[80px] rounded-[50px] max-lg:rounded-[40px] max-md:rounded-[35px] max-sm:rounded-[30px] rotate-[-16.63deg] bg-primary-orange w-[777px] max-xl:w-[650px] max-lg:w-[600px] max-md:w-[500px] max-sm:w-[400px] h-[282px] max-lg:h-[250px] max-md:h-[220px] max-sm:h-[180px] transition-all duration-300"
                    :class="isHovered ? 'top-[280px] max-lg:top-[250px] max-md:top-[220px] max-sm:top-[180px]' : 'top-[380px] max-lg:top-[350px] max-md:top-[300px] max-sm:top-[250px] card-mobile' ">
                    <div class="relative w-full h-full">
                        <h2
                            class="absolute rotate-[16.63deg] top-8 max-lg:top-6 max-md:top-5 max-sm:top-4 right-8 max-lg:right-6 max-md:right-5 max-sm:right-4 text-[32px] max-lg:text-[26px] max-md:text-[22px] max-sm:text-[18px] text-white font-semibold font-custom!">
                            {{ request('lang') == 'en' ? $espacio->name_en : $espacio->name_es }}
                        </h2>
                    </div>
                </div>
                <a href="/productos?espacio={{ $espacio->id }}"
                    class="absolute rounded-sm bottom-12 max-lg:bottom-10 max-md:bottom-8 max-sm:bottom-6 right-12 max-lg:right-10 max-md:right-8 max-sm:right-6 text-[14px] max-md:text-[13px] max-sm:text-[12px] font-medium px-3 max-sm:px-2 py-2 max-sm:py-1.5 bg-white text-primary-orange transition-all duration-300 hover:bg-gray-50 max-sm:opacity-100"
                    :class="isHovered ? 'opacity-100' : 'opacity-0 max-sm:opacity-100'">
                    {{__('VER TODA LA COLECCIÓN')}}
                </a>

                <!-- Touch/tap functionality for mobile -->
                <div class="lg:hidden absolute inset-0 cursor-pointer"
                    x-on:click="window.location.href = '/productos?espacio={{ $espacio->id }}'">
                </div>
            </div>
        @endforeach
    </div>
</div>