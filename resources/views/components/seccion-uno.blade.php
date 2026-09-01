<div
    class="flex justify-between max-lg:flex-col gap-6 max-lg:gap-8 max-md:gap-6 max-sm:gap-4 py-10 max-lg:py-8 max-md:py-6 max-sm:py-4 px-6 max-lg:px-4 max-md:px-3 max-sm:px-3">

    <div
        class="w-full overflow-hidden rounded-tl-[70px] max-lg:rounded-tl-[50px] max-md:rounded-tl-[40px] max-sm:rounded-tl-[30px] rounded-br-[70px] max-lg:rounded-br-[50px] max-md:rounded-br-[40px] max-sm:rounded-br-[30px] max-h-[440px] max-lg:max-h-[350px] max-md:max-h-[300px] max-sm:max-h-[250px] border max-lg:order-2">
        <img class="h-full w-full object-cover" src="{{$homeInfo->image_seccion_uno}}" alt="">
    </div>

    <div
        class="w-full flex flex-col items-start max-lg:items-center max-lg:text-center gap-5 max-lg:gap-4 max-md:gap-3 max-sm:gap-3 py-5 max-lg:py-0 max-lg:order-1">
        <h2
            class="text-[32px] max-lg:text-[28px] max-md:text-[24px] max-sm:text-[20px] font-semibold font-custom! max-w-[569px] max-lg:max-w-full leading-tight">
            {{request('lang') == 'en' ? $homeInfo->title_seccion_uno_en : $homeInfo->title_seccion_uno_es}}
        </h2>
        <p
            class="max-w-[569px] max-lg:max-w-full text-[16px] max-md:text-[15px] max-sm:text-[14px] leading-relaxed text-gray-700">
            {{request('lang') == 'en' ? $homeInfo->text_seccion_uno_en : $homeInfo->text_seccion_uno_es}}
        </p>
        <a href="{{ request('lang') == 'en' ? '/comercio-exterior?lang=en' : '/comercio-exterior?lang=es' }}"
            class="flex justify-center items-center w-[280px] max-lg:w-[300px] max-md:w-[280px] max-sm:w-full max-sm:max-w-[280px] h-[42px] max-sm:h-[44px] bg-primary-orange rounded-sm font-medium text-[14px] max-sm:text-[13px] text-white hover:bg-primary-orange/90 transition-colors duration-300 mt-2 max-lg:mt-1">
            {{__("DESCUBRI COMO TRABAJAMOS")}}
        </a>
    </div>

</div>
