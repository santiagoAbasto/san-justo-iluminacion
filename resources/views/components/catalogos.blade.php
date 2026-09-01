<div
    class="w-full h-auto min-h-[376px] max-lg:min-h-[320px] max-md:min-h-[280px] bg-[#F2EEED] flex items-center justify-center py-10 max-lg:py-8 max-md:py-6 max-sm:py-5 px-6 max-lg:px-4 max-md:px-3 max-sm:px-3">
    <div class="flex flex-col gap-5 max-lg:gap-4 max-md:gap-3 w-full max-w-[1200px]">
        <h2
            class="text-[32px] max-lg:text-[28px] max-md:text-[24px] max-sm:text-[20px] font-semibold font-custom! text-center">
            {{request('lang') == 'en' ? $titulo->title_en : $titulo->title_es}}
        </h2>
        <div
            class="grid grid-cols-2 max-lg:grid-cols-1 gap-12 max-lg:gap-8 max-md:gap-6 max-sm:gap-4 w-full justify-items-center">
            @foreach ($catalogos as $catalogo)
                <div
                    class="bg-white h-[214px] max-lg:h-auto min-h-[200px] max-lg:min-h-[180px] max-sm:min-h-[160px] w-full max-w-[392px] max-lg:max-w-[500px] max-md:max-w-full rounded-tl-[36px] max-lg:rounded-tl-[30px] max-md:rounded-tl-[24px] max-sm:rounded-tl-[20px] rounded-br-[36px] max-lg:rounded-br-[30px] max-md:rounded-br-[24px] max-sm:rounded-br-[20px] flex flex-col gap-5 max-lg:gap-4 max-md:gap-3 p-6 max-lg:p-5 max-md:p-4 max-sm:p-3 shadow-sm">
                    <h3
                        class="text-[20px] max-lg:text-[18px] max-md:text-[16px] max-sm:text-[15px] font-bold leading-tight">
                        {{request('lang') == 'en' ? $catalogo->name_en : $catalogo->name_es}}
                    </h3>
                    <p class="text-[16px] max-md:text-[15px] max-sm:text-[14px] text-gray-600 leading-relaxed flex-1">
                        {{request('lang') == 'en' ? $catalogo->subtitle_en : $catalogo->subtitle_es}}
                    </p>
                    <div class="flex flex-row max-sm:flex-col gap-6 max-lg:gap-4 max-md:gap-3 max-sm:gap-2 mt-auto">
                        <a href="{{ "storage/" . $catalogo->archivo }}" target="_blank"
                            class="text-[14px] max-sm:text-[13px] w-[156px] max-lg:w-[140px] max-md:w-[130px] max-sm:w-full h-[42px] max-sm:h-[40px] font-medium border border-primary-orange rounded-sm text-primary-orange hover:bg-primary-orange hover:text-white transition duration-300 flex items-center justify-center">
                            {{__("VER ONLINE")}}
                        </a>
                        <a href="{{ asset("storage/" . $catalogo->archivo) }}"
                            download="{{ request('lang') == 'en' ? $catalogo->name_en : $catalogo->name_es  }}"
                            class="text-[14px] max-sm:text-[13px] text-white font-medium bg-primary-orange w-[156px] max-lg:w-[140px] max-md:w-[130px] max-sm:w-full h-[42px] max-sm:h-[40px] rounded-sm hover:border hover:border-primary-orange hover:bg-transparent hover:text-primary-orange transition duration-300 flex items-center justify-center">
                            {{__("DESCARGAR")}}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>