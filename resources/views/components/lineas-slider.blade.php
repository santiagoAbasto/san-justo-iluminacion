<div class="py-16 max-lg:py-12 max-md:py-10 max-sm:py-8">
    <div
        class="flex flex-col gap-9 max-lg:gap-7 max-md:gap-6 max-sm:gap-5 max-w-[90%] lg:max-w-[1224px] max-xl:max-w-[1100px] mx-auto px-4 max-lg:px-3 max-md:px-2">
        <div class="flex">
            <h2
                class="text-[32px] max-lg:text-[28px] max-md:text-[24px] max-sm:text-[20px] font-semibold font-custom! text-left text-black">
                {{request('lang') == 'en' ? $titulo->title_en : $titulo->title_es}}
            </h2>
        </div>
        <div class="relative" x-data="{
            activeSlide: 0,
            totalSlides: 0,
            autoSlideInterval: null,
            isMobile: window.innerWidth < 1024,
            isTablet: window.innerWidth >= 768 && window.innerWidth < 1024,
            lineasCount: {{ count($lineas) }},
        
            init() {
                this.calculateTotalSlides();
                this.startAutoSlide();
        
                // Actualizar cuando cambie el tamaño de la ventana
                window.addEventListener('resize', () => {
                    this.isMobile = window.innerWidth < 1024;
                    this.isTablet = window.innerWidth >= 768 && window.innerWidth < 1024;
                    this.calculateTotalSlides();
                    this.activeSlide = 0; // Reiniciar a la primera diapositiva al cambiar de tamaño
                    this.stopAutoSlide();
                    this.startAutoSlide();
                });
            },
        
            calculateTotalSlides() {
                if (this.isMobile) {
                    if (this.isTablet) {
                        this.totalSlides = Math.ceil(this.lineasCount / 2);
                    } else {
                        this.totalSlides = this.lineasCount;
                    }
                } else {
                    this.totalSlides = Math.ceil(this.lineasCount / 3);
                }
                console.log('Total slides:', this.totalSlides, 'Is mobile:', this.isMobile, 'Is tablet:', this.isTablet);
            },
        
            startAutoSlide() {
                if (this.totalSlides <= 1) return; // No iniciar si solo hay 1 slide
        
                this.autoSlideInterval = setInterval(() => {
                    this.nextSlide();
                }, this.isMobile ? 3000 : 5000);
            },
        
            stopAutoSlide() {
                if (this.autoSlideInterval) {
                    clearInterval(this.autoSlideInterval);
                    this.autoSlideInterval = null;
                }
            },
        
            nextSlide() {
                if (this.totalSlides <= 1) return;
        
                this.activeSlide = this.activeSlide + 1;
                if (this.activeSlide >= this.totalSlides) {
                    this.activeSlide = 0;
                }
                console.log('Next slide:', this.activeSlide, 'of', this.totalSlides);
            },
        
            prevSlide() {
                if (this.totalSlides <= 1) return;
        
                this.activeSlide = this.activeSlide - 1;
                if (this.activeSlide < 0) {
                    this.activeSlide = this.totalSlides - 1;
                }
            },
        
            goToSlide(index) {
                if (index >= 0 && index < this.totalSlides) {
                    this.activeSlide = index;
                }
            }
        }" @mouseover="stopAutoSlide()" @mouseleave="startAutoSlide()">

            <!-- Carrusel de clientes -->
            <div class="relative">
                <!-- Versión para escritorio (oculta en móvil y tablet) -->
                <div class="hidden lg:block overflow-hidden">
                    <div class="flex transition-transform duration-500 ease-in-out"
                        :style="'transform: translateX(-' + (activeSlide * 100) + '%)'">

                        @php
                            $chunkedLineas = $lineas->chunk(3);
                        @endphp

                        @foreach ($chunkedLineas as $chunk)
                            <div class="grid grid-cols-3 justify-between gap-6 min-w-full">
                                @foreach ($chunk as $linea)
                                    <div class="w-[392px] h-[554px] flex flex-col justify-between">
                                        <div
                                            class="w-[392px] min-h-[392px] rounded-tr-[70px] rounded-bl-[70px] overflow-hidden">
                                            @if ($linea->image)
                                                <img src="{{ asset("storage/" . $linea->image) }}" alt="cliente"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-500">
                                                    Sin imagen
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex flex-col h-full pt-5">
                                            <h2 class="text-[24px] font-medium">
                                                {{request('lang') == 'en' ? $linea->name_en : $linea->name_es}}
                                            </h2>
                                            <div class="text-[15px] font-light line-clamp-2 overflow-hidden">
                                                {!! request('lang') == 'en' ? $linea->text_en : $linea->text_es !!}
                                            </div>
                                        </div>
                                        <a class="flex flex-row gap-2 items-center font-medium text-[16px] hover:text-primary-orange transition-colors"
                                            href="/productos?linea={{ $linea->id }}">{{__("Ver productos")}} <span><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 16 16" fill="none">
                                                    <path d="M8 0L6.59 1.41L12.17 7H0V9H12.17L6.59 14.59L8 16L16 8L8 0Z"
                                                        fill="currentColor" />
                                                </svg></span></a>
                                    </div>
                                @endforeach

                                <!-- Agrega divs vacíos para mantener la estructura si hay menos de 3 items en el chunk -->
                                @for ($i = count($chunk); $i < 3; $i++)
                                    <div class="w-[392px] h-[554px]"></div>
                                @endfor
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Versión para tablet (768px - 1024px) -->
                <div class="hidden md:block lg:hidden overflow-hidden">
                    <div class="flex transition-transform duration-500 ease-in-out"
                        :style="'transform: translateX(-' + (activeSlide * 100) + '%)'">

                        @php
                            $chunkedLineasTablet = $lineas->chunk(2);
                        @endphp

                        @foreach ($chunkedLineasTablet as $chunk)
                            <div class="grid grid-cols-2 gap-4 min-w-full justify-center">
                                @foreach ($chunk as $linea)
                                    <div class="max-w-[350px] w-full mx-auto flex flex-col justify-between h-[480px]">
                                        <div class="w-full min-h-[300px] rounded-tr-[70px] rounded-bl-[70px] overflow-hidden">
                                            @if ($linea->image)
                                                <img src="{{ asset("storage/" . $linea->image) }}" alt="cliente"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-500">
                                                    Sin imagen
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex flex-col pt-4 flex-1">
                                            <h2 class="text-[20px] font-medium mb-2">
                                                {{request('lang') == 'en' ? $linea->name_en : $linea->name_es}}
                                            </h2>
                                            <div class="text-[14px] font-light line-clamp-2 overflow-hidden mb-3 flex-1">
                                                {!! request('lang') == 'en' ? $linea->text_en : $linea->text_es !!}
                                            </div>
                                            <a class="flex flex-row gap-2 items-center font-medium text-[15px] hover:text-primary-orange transition-colors mt-auto"
                                                href="/productos?linea={{ $linea->id }}">{{__("Ver productos")}} <span><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                        viewBox="0 0 16 16" fill="none">
                                                        <path d="M8 0L6.59 1.41L12.17 7H0V9H12.17L6.59 14.59L8 16L16 8L8 0Z"
                                                            fill="currentColor" />
                                                    </svg></span></a>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Agregar div vacío si solo hay un item en el chunk -->
                                @if (count($chunk) === 1)
                                    <div class="max-w-[350px] w-full mx-auto"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Versión para móvil (menos de 768px) -->
                <div class="md:hidden overflow-hidden">
                    <div class="flex transition-transform duration-500 ease-in-out"
                        :style="'transform: translateX(-' + (activeSlide * 100) + '%)'">

                        @foreach ($lineas as $linea)
                            <div class="min-w-full flex justify-center px-2">
                                <div
                                    class="max-w-[300px] w-full bg-white rounded-tr-[70px] rounded-bl-[70px] max-sm:rounded-none overflow-hidden shadow-sm">
                                    <div class="w-full h-[200px] max-sm:h-[180px]">
                                        @if ($linea->image)
                                            <img src="{{ asset("storage/" . $linea->image) }}" alt="cliente"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-500">
                                                Sin imagen
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-4 max-sm:p-3">
                                        <h2 class="text-[18px] max-sm:text-[16px] font-medium mb-2 leading-tight">
                                            {{request('lang') == 'en' ? $linea->name_en : $linea->name_es}}
                                        </h2>
                                        <div
                                            class="text-[13px] max-sm:text-[12px] font-light line-clamp-2 overflow-hidden mb-3 text-gray-600 leading-relaxed">
                                            {!! request('lang') == 'en' ? $linea->text_en : $linea->text_es !!}
                                        </div>
                                        <a class="flex flex-row gap-2 items-center font-medium text-[14px] max-sm:text-[13px] hover:text-primary-orange transition-colors"
                                            href="/productos?linea={{ $linea->id }}">{{__("Ver productos")}} <span><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    viewBox="0 0 16 16" fill="none">
                                                    <path d="M8 0L6.59 1.41L12.17 7H0V9H12.17L6.59 14.59L8 16L16 8L8 0Z"
                                                        fill="currentColor" />
                                                </svg></span></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Controles de navegación para móvil/tablet -->
            <div class="lg:hidden flex justify-between items-center mt-6 max-sm:mt-4">
                <button @click="prevSlide()"
                    class="p-2 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors disabled:opacity-50"
                    :disabled="totalSlides <= 1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M15 18l-6-6 6-6" />
                    </svg>
                </button>

                <div class="flex items-center space-x-1">
                    <span class="text-sm text-gray-600" x-text="(activeSlide + 1)"></span>
                    <span class="text-sm text-gray-400">/</span>
                    <span class="text-sm text-gray-600" x-text="totalSlides"></span>
                </div>

                <button @click="nextSlide()"
                    class="p-2 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors disabled:opacity-50"
                    :disabled="totalSlides <= 1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M9 18l6-6-6-6" />
                    </svg>
                </button> <!-- ✅ Cerrar el botón correctamente -->
            </div> <!-- ✅ Cerrar el contenedor de controles de navegación -->

            <!-- Indicadores de paginación con forma de barras (solo desktop) -->
            <template x-if="totalSlides > 1">
                <div class="hidden lg:flex justify-center space-x-2 mt-16 max-lg:mt-12">
                    <template x-for="(slide, index) in Array.from({length: totalSlides})" :key="index">
                        <button @click="goToSlide(index)"
                            :class="{ 'bg-gray-800': activeSlide === index, 'bg-gray-300': activeSlide !== index }"
                            class="w-10 h-1.5 rounded-full cursor-pointer transition-colors duration-300 hover:bg-gray-600">
                        </button>
                    </template>
                </div>
            </template>
        </div>
    </div>

    <!-- Indicadores de paginación con forma de barras (solo desktop) -->
    <template x-if="totalSlides > 1">
        <div class="hidden lg:flex justify-center space-x-2 mt-16 max-lg:mt-12">
            <template x-for="(slide, index) in Array.from({length: totalSlides})" :key="index">
                <button @click="goToSlide(index)"
                    :class="{ 'bg-gray-800': activeSlide === index, 'bg-gray-300': activeSlide !== index }"
                    class="w-10 h-1.5 rounded-full cursor-pointer transition-colors duration-300 hover:bg-gray-600"></button>
            </template>
        </div>
    </template>
</div>
</div>
</div>
