<div class="py-16">
    <div class="flex flex-col gap-9 max-w-[90%] lg:max-w-[1224px] mx-auto">
        <div class="flex ">
            <h2 class="text-[32px] font-semibold font-custom! text-left text-black">
                {{request('lang') == 'en' ? $titulo->title_en : $titulo->title_es}}
            </h2>
        </div>
        <div class="relative" x-data="{
            activeSlide: 0,
            totalSlides: 0,
            autoSlideInterval: null,
            isMobile: window.innerWidth < 1024,
            clientesCount: {{ count($clientes) }},
        
            init() {
                this.calculateTotalSlides();
                this.startAutoSlide();
        
                // Actualizar cuando cambie el tamaño de la ventana
                window.addEventListener('resize', () => {
                    this.isMobile = window.innerWidth < 1024;
                    this.calculateTotalSlides();
                    this.activeSlide = 0; // Reiniciar a la primera diapositiva al cambiar de tamaño
                    this.stopAutoSlide();
                    this.startAutoSlide();
                });
            },
        
            calculateTotalSlides() {
                if (this.isMobile) {
                    this.totalSlides = this.clientesCount;
                } else {
                    this.totalSlides = Math.ceil(this.clientesCount / 6); // Cambiado a 6 según tu grid-cols-6
                }
                console.log('Total slides:', this.totalSlides, 'Is mobile:', this.isMobile);
            },
        
            startAutoSlide() {
                if (this.totalSlides <= 1) return; // No iniciar si solo hay 1 slide
        
                this.autoSlideInterval = setInterval(() => {
                    this.nextSlide();
                }, this.isMobile ? 3000 : 10000);
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
                    this.stopAutoSlide(); // Parar auto-slide cuando el usuario interactúa
                    this.startAutoSlide(); // Reiniciar auto-slide después de la interacción
                }
            },

            // Método para obtener el transform style
            getTransformStyle() {
                return `transform: translateX(-${this.activeSlide * 100}%)`;
            }
        }" @mouseover="stopAutoSlide()" @mouseleave="startAutoSlide()">

            <!-- Carrusel de clientes -->
            <div class="overflow-hidden relative">
                <!-- Versión para escritorio (oculta en móvil) -->
                <div class="hidden lg:flex transition-transform duration-500 ease-in-out" :style="getTransformStyle()">

                    @php
                        $chunkedClientes = $clientes->chunk(6);
                    @endphp

                    @foreach ($chunkedClientes as $chunk)
                        <div class="grid grid-cols-6 justify-between gap-6 min-w-full">
                            @foreach ($chunk as $cliente)
                                <div class="h-[122px] w-[184px] border rounded-tl-[36px] rounded-br-[36px] overflow-hidden">
                                    <img src="{{ asset("storage/" . $cliente->image) }}" alt="cliente"
                                        class="w-full h-full object-contain transition-all duration-300 filter grayscale hover:grayscale-0">
                                </div>
                            @endforeach

                            <!-- Agrega divs vacíos para mantener la estructura si hay menos de 6 items en el chunk -->
                            @for ($i = count($chunk); $i < 6; $i++)
                                <div class="h-[122px] max-w-[184px] "></div>
                            @endfor
                        </div>
                    @endforeach
                </div>

                <!-- Versión para móvil (oculta en escritorio) -->
                <div class="lg:hidden flex transition-transform duration-500 ease-in-out" :style="getTransformStyle()">

                    @foreach ($clientes as $cliente)
                        <div class="min-w-full flex justify-center">
                            <div class="max-h-[190px] max-w-[300px] w-full bg-white">
                                <img src="{{ asset("storage/" . $cliente->image) }}" alt="cliente"
                                    class="w-full h-full object-cover transition-all duration-300 filter lg:grayscale hover:grayscale-0">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Indicadores de paginación con forma de barras -->
            <template x-if="totalSlides > 1">
                <div class="absolute -bottom-10 left-0 right-0 flex justify-center space-x-2 mt-6">
                    <template x-for="(slide, index) in Array.from({length: totalSlides})" :key="index">
                        <button @click="goToSlide(index)"
                            :class="{ 'bg-gray-400': activeSlide === index, 'bg-gray-200': activeSlide !== index }"
                            class="w-10 h-1.5 cursor-pointer transition-colors duration-300 hover:bg-gray-500"></button>
                    </template>
                </div>
            </template>
        </div>
    </div>
</div>