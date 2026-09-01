<div style="background-image: url({{$homeInfo->image_seccion_dos}})"
    class="w-full flex items-center h-[520px] sm:h-[420px] md:h-[480px] lg:h-[520px] bg-no-repeat bg-cover bg-center">
    <div
        class="h-[218px] sm:h-auto sm:py-8 md:h-[200px] lg:h-[218px] bg-black/60 flex flex-col justify-center gap-3 sm:gap-4 md:gap-3 w-full items-center px-4 sm:px-6 md:px-8">
        <h2
            class="text-[32px] sm:text-[24px] md:text-[28px] lg:text-[32px] font-semibold font-custom! text-white leading-tight text-center">
            {{request('lang') == 'en' ? $homeInfo->title_seccion_dos_en : $homeInfo->title_seccion_dos_es}}
        </h2>
        <p
            class="text-[20px] sm:text-[16px] md:text-[18px] lg:text-[20px] text-white max-w-[642px] sm:max-w-[90%] md:max-w-[80%] lg:max-w-[642px] text-center leading-tight px-2">
            {{request('lang') == 'en' ? $homeInfo->text_seccion_dos_en : $homeInfo->text_seccion_dos_es}}
        </p>
        <a href="/nosotros"
            class="flex items-center justify-center text-[14px] sm:text-[13px] md:text-[14px] text-white font-medium py-2 px-3 sm:py-3 sm:px-4 md:py-2 md:px-3 bg-primary-orange rounded-sm">
            {{__("CONOCENOS")}}
        </a>
    </div>
</div>