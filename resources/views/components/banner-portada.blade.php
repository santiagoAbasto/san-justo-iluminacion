<div class="overflow-hidden min-h-[655px] max-sm:min-h-[500px]">
    <div class="slider-track h-[655px] max-sm:h-fit flex transition-transform duration-500 ease-in-out justify-center">

        @php $ext = pathinfo($homeInfo->image_banner, PATHINFO_EXTENSION); @endphp
        <div class="slider-item min-w-full relative h-[655px]  max-sm:h-[500px]">
            <div class="absolute inset-0 bg-black z-0">
                @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                    <img src="{{ $homeInfo->image_banner }}" alt="Slider Image" class="w-full h-full object-cover"
                        data-duration="6000">
                @elseif (in_array($ext, ['mp4', 'webm', 'ogg']))
                    <video class="w-full h-full object-cover object-center" loop autoplay muted>
                        <source src="{{ $homeInfo->image_banner }}" type="video/{{ $ext }}">
                        {{ __('Tu navegador no soporta el formato de video.') }}
                    </video>
                @endif
            </div>
            <div class="absolute inset-0 bg-black/50 z-10"></div>
            <div class="absolute inset-0 flex z-20 lg:max-w-[1200px] lg:mx-auto max-sm:px-4">
                <div
                    class="relative flex flex-col gap-4 sm:gap-6 lg:gap-19 max-sm:gap-3 w-full justify-center items-center ">
                    <div
                        class="w-fit items-center text-center justify-center max-sm:max-w-full text-white flex flex-col gap-8">

                        <h1 class="text-[32px] font-bold w-fit font-custom! leading-none">
                            {{ request('lang') == 'en' ? $homeInfo->title_banner_en : $homeInfo->title_banner_es }}
                        </h1>
                        <h3 class="text-[20px] max-w-[524px] ">
                            {{request('lang') == 'en' ? $homeInfo->text_banner_en : $homeInfo->text_banner_es}}
                        </h3>


                        <a href="/productos"
                            class="flex justify-center items-center w-[280px] h-[42px] bg-primary-orange rounded-sm font-medium text-[14px]">{{__('CONOCE NUESTROS PRODUCTOS')}}</a>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <!-- Slider Navigation Dots -->

</div>