@php
    $isHome = request()->is('/');
    $isPrivate = str_contains(request()->path(), 'privada');
    $logoPrincipal = $logos->logo_principal ?? null;
    $logoSecundario = $logos->logo_tres ?? $logoPrincipal;
    $initialLogo = $isHome ? ($logoPrincipal ?: $logoSecundario) : ($logoSecundario ?: $logoPrincipal);

    $defaultLinks = [
        ['title' => __('PRODUCTOS'), 'href' => '/productos'],
        ['title' => __('NOSOTROS'), 'href' => '/nosotros'],
        ['title' => __('TRABAJA CON NOSOTROS'), 'href' => '/trabaja-con-nosotros'],
        ['title' => __('DÓNDE COMPRAR'), 'href' => '/donde-comprar'],
        ['title' => __('COMERCIO EXTERIOR'), 'href' => '/comercio-exterior'],
        ['title' => __('CONTACTO'), 'href' => '/contacto'],
    ];
@endphp

<div x-data="{
        lang: '{{ request('lang') == 'en' ? 'en' : 'es' }}',
        showModal: false,
        modalType: 'login',
        scrolled: false,
        searchOpen: false,
        mobileMenuOpen: false,
        espacioSelected: null,
        logoPrincipal: @js($logoPrincipal),
        logoSecundario: @js($logoSecundario),
        switchToLogin() { this.modalType = 'login' },
        switchToRegister() { this.modalType = 'register' },
        openModal(type = 'login') { this.modalType = type; this.showModal = true },
        closeModal() { this.showModal = false },
        toggleMobileMenu() { this.mobileMenuOpen = !this.mobileMenuOpen },
        selectEspacio(espacio) { this.espacioSelected = espacio }
    }"
    x-init="
        @if ($isHome)
            window.addEventListener('scroll', () => { scrolled = window.scrollY > 0 });
        @else
            scrolled = true;
        @endif
    "
    :class="{
        'bg-white shadow-md': scrolled || !{{ $isHome ? 'true' : 'false' }},
        'bg-transparent': !scrolled && {{ $isHome ? 'true' : 'false' }},
        'fixed top-0': {{ $isHome ? 'true' : 'false' }},
        'sticky top-0': {{ $isHome ? 'false' : 'true' }}
    }"
    class="z-1001 sticky top-0 w-full transition-colors duration-300 h-[100px] max-sm:h-auto flex flex-col">

    <!-- NAVBAR DESKTOP -->
    <div class="mx-auto flex h-[96px] max-lg:h-[80px] max-md:h-[70px] max-sm:h-[60px] w-[1224px] max-w-[1224px] max-xl:w-full max-xl:px-6 max-lg:px-4 max-sm:px-3 items-center justify-between">
        <!-- Logo -->
        <div class="flex flex-row items-center h-fit gap-10 max-lg:gap-6 max-md:gap-4 w-full justify-between">
            <a class="min-w-[258px] flex max-sm:pt-4 items-end self-end max-lg:min-w-[200px] max-md:min-w-[180px] max-sm:min-w-[150px] min-h-[57px] max-lg:min-h-[45px] max-md:min-h-[40px] max-sm:min-h-[35px]"
               href="{{ request('lang') == 'en' ? '/?lang=en' : '/?lang=es' }}">
                @if($initialLogo)
                    <img class="w-full h-full object-contain img-header"
                         src="{{ $initialLogo }}"
                         :src="scrolled ? logoSecundario : logoPrincipal"
                         fetchpriority="high"
                         decoding="async"
                         alt="Logo" />
                @endif
            </a>

            <!-- Botón hamburguesa móvil -->
            <button @click="toggleMobileMenu()"
                    class="lg:hidden flex flex-col items-center justify-center w-8 h-8 max-sm:w-6 max-sm:h-6 space-y-1 max-sm:space-y-0.5"
                    :class="scrolled ? 'text-black' : 'text-white'">
                <span class="block w-6 max-sm:w-5 h-0.5 bg-current transition-all duration-300"
                      :class="{ 'rotate-45 translate-y-2 max-sm:translate-y-1.5': mobileMenuOpen }"></span>
                <span class="block w-6 max-sm:w-5 h-0.5 bg-current transition-all duration-300"
                      :class="{ 'opacity-0': mobileMenuOpen }"></span>
                <span class="block w-6 max-sm:w-5 h-0.5 bg-current transition-all duration-300"
                      :class="{ '-rotate-45 -translate-y-2 max-sm:-translate-y-1.5': mobileMenuOpen }"></span>
            </button>

            <!-- Navegación desktop -->
            <div class="flex flex-col items-end justify-between text-white leading-none h-full gap-5 max-w-full max-lg:hidden">
                <div class="flex flex-row gap-4 items-center shrink-0" :class="scrolled ? 'text-black' : 'text-white'">
                    <select onchange="window.location.href = window.location.pathname + this.value"
                            class="border-none bg-transparent outline-none">
                        <option class="text-black" value="?lang=es" {{ request('lang') === 'es' ? 'selected' : '' }}>ES</option>
                        <option class="text-black" value="?lang=en" {{ request('lang') === 'en' ? 'selected' : '' }}>EN</option>
                    </select>
                    <span>|</span>
                    <a href="{{ route('login') }}" target="_blank"
                       class="border flex items-center justify-center rounded-sm text-[14px] leading-none w-[136px] h-[42px]"
                       :class="scrolled ? 'border-black text-black' : 'border-white text-white'">
                        {{ __('AREA CLIENTE') }}
                    </a>
                </div>

                <div class="flex gap-6 max-xl:gap-4 items-end flex-wrap" x-data="{ openProductos: false }">
                    @foreach(($isPrivate ? $privateLinks : $defaultLinks) as $link)
                        @if($link['title'] === __('PRODUCTOS'))
                            <div @click.away="openProductos = false">
                                <button type="button" @click="openProductos = !openProductos; espacioSelected = null"
                                    class="flex items-center gap-1 text-[15px] max-xl:text-[14px] transition-colors duration-300 whitespace-nowrap leading-none
                                           {{ Request::is(ltrim($link['href'], '/')) ? 'font-medium  text-black' : '' }}"
                                    :class="scrolled ? 'text-black' : 'text-white'">
                                    {{ $link['title'] }}
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                         stroke="currentColor" class="w-4 h-4 transition-transform duration-200"
                                         :class="{ 'rotate-180': openProductos }">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>

                                <!-- Dropdown -->
                                <div x-show="openProductos" x-transition
                                     class="absolute w-screen left-1/2 -translate-x-1/2 mt-3 h-[243px] p-10 max-xl:p-6 flex flex-row gap-x-10 max-xl:gap-x-6 bg-black text-white z-50">
                                    <div class="w-[1224px] max-w-[1224px] max-xl:w-full mx-auto flex flex-row gap-10 max-xl:gap-6">
                                        <div class="flex flex-col gap-6 max-xl:gap-4">
                                            @foreach ($espacios as $espacio)
                                                <div class="flex flex-row items-center cursor-pointer transition-colors"
                                                     @click="selectEspacio({{ json_encode($espacio) }})">
                                                    <button class="text-[15px] max-xl:text-[14px] font-barlow uppercase">
                                                        {{ request('lang') == 'en' ? $espacio->name_en : $espacio->name_es }}
                                                    </button>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                         stroke-linecap="round" stroke-linejoin="round"
                                                         class="lucide lucide-chevron-right ml-2">
                                                        <path d="m9 18 6-6-6-6" />
                                                    </svg>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Segunda columna: Usos -->
                                        <div x-show="espacioSelected" x-transition
                                             class="grid grid-cols-4 max-xl:grid-cols-3 grid-rows-5 grid-flow-col h-fit w-full gap-y-6 max-xl:gap-y-4">
                                            <template x-for="uso in (espacioSelected && espacioSelected.usos ? espacioSelected.usos : [])" :key="uso.id">
                                                <a :href="'/productos?espacio=' + uso.espacio_id + '&uso=' + uso.id + (lang == 'en' ? '&lang=en' : '&lang=es') "
                                                   class="text-[15px] max-xl:text-[14px] font-barlow! h-full uppercase"
                                                   x-text="uso.name_{{ request('lang') == 'en' ? 'en' : 'es' }}"></a>
                                            </template>
                                            <a :href="espacioSelected ? '/productos?espacio=' + espacioSelected.id + (lang == 'en' ? '&lang=en' : '&lang=es') : '#'"
                                               class="text-[15px] max-xl:text-[14px] font-barlow! h-full uppercase">
                                                {{ __('TODOS') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ request('lang') == 'en' ? $link['href'].'?lang=en' : $link['href'].'?lang=es' }}"
                               :class="scrolled ? 'text-black' : 'text-white'"
                               class="text-[15px] max-xl:text-[14px] hover:text-primary-orange transition-colors duration-300 whitespace-nowrap leading-none
                                      {{ Request::is(ltrim($link['href'],'/')) ? 'font-medium  text-black' : '' }}">
                                {{ $link['title'] }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- MENÚ MÓVIL -->
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="lg:hidden absolute top-20 w-full bg-white border-t border-gray-200 shadow-lg z-40"
         @click.away="mobileMenuOpen = false">

        <!-- Header móvil -->
        <div class="px-4 py-3 max-sm:px-3 max-sm:py-2 border-b border-gray-100 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <select onchange="window.location.href = window.location.pathname + this.value"
                            class="border border-gray-300 rounded px-2 py-1 text-sm bg-white">
                        <option value="?lang=es" {{ request('lang') === 'es' ? 'selected' : '' }}>ES</option>
                        <option value="?lang=en" {{ request('lang') === 'en' ? 'selected' : '' }}>EN</option>
                    </select>
                </div>
                <a href="{{ route('login') }}" target="_blank"
                   class="border flex items-center justify-center rounded-sm text-[14px] leading-none w-[136px] h-[42px]"
                   :class="scrolled ? 'border-black text-black' : 'border-white text-white'">
                   {{ __('AREA CLIENTE') }}
                </a>
            </div>
        </div>

        <!-- Enlaces de navegación móvil -->
        <div class="py-2 max-h-[calc(100vh-120px)] max-sm:max-h-[calc(100vh-100px)] overflow-y-auto">
            @foreach(($isPrivate ? $privateLinks : $defaultLinks) as $link)
                @if($link['title'] === __('PRODUCTOS'))
                    <div x-data="{ openProductosMobile: false }">
                        <button @click="openProductosMobile = !openProductosMobile"
                                class="w-full flex items-center justify-between px-4 py-3 max-sm:px-3 max-sm:py-2 text-sm max-sm:text-xs text-gray-700 hover:bg-gray-50 hover:text-primary-orange transition-colors duration-300 border-b border-gray-100
                                       {{ Request::is(ltrim($link['href'], '/')) ? 'font-medium  bg-orange-50 text-primary-orange' : '' }}">
                            <span>{{ $link['title'] }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-4 h-4 transition-transform duration-200"
                                 :class="{ 'rotate-180': openProductosMobile }">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <!-- Submenú productos móvil -->
                        <div x-show="openProductosMobile" x-transition class="bg-gray-50 border-b border-gray-100">
                            @foreach ($espacios as $espacio)
                                <div x-data="{ openEspacioMobile: false }" class="border-b border-gray-200 last:border-b-0">
                                    <button @click="openEspacioMobile = !openEspacioMobile"
                                            class="w-full flex items-center justify-between px-6 py-2 max-sm:px-4 text-xs max-sm:text-[11px] text-gray-600 hover:bg-gray-100 transition-colors">
                                        <span class="uppercase">{{ request('lang') == 'en' ? $espacio->name_en : $espacio->name_es }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round" class="transition-transform duration-200"
                                             :class="{ 'rotate-90': openEspacioMobile }">
                                            <path d="m9 18 6-6-6-6" />
                                        </svg>
                                    </button>

                                    <div x-show="openEspacioMobile" x-transition class="bg-white">
                                        @if(isset($espacio->usos))
                                            @foreach($espacio->usos as $uso)
                                                <a href="/productos?espacio={{ $uso->espacio_id }}&uso={{ $uso->id }}"
                                                   class="block px-8 py-2 max-sm:px-6 text-xs max-sm:text-[11px] text-gray-500 hover:bg-gray-50 hover:text-primary-orange transition-colors uppercase"
                                                   @click="mobileMenuOpen = false">
                                                    {{ request('lang') == 'en' ? $uso->name_en : $uso->name_es }}
                                                </a>
                                            @endforeach
                                            <a href="{{ request('lang') == 'en' ? '/productos?espacio=' . $espacio->id . '&lang=en' : '/productos?espacio=' . $espacio->id . '&lang=es' }}"
                                               class="block px-8 py-2 max-sm:px-6 text-xs max-sm:text-[11px] text-gray-500 hover:bg-gray-50 hover:text-primary-orange transition-colors uppercase"
                                               @click="mobileMenuOpen = false">
                                                {{ __('TODOS') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ request('lang') == 'en' ? $link['href'].'?lang=en' : $link['href'].'?lang=es' }}"
                       class="block px-4 py-3 max-sm:px-3 max-sm:py-2 text-sm max-sm:text-xs text-gray-700 hover:bg-gray-50 hover:text-primary-orange transition-colors duration-300 border-b border-gray-100 last:border-b-0
                              {{ Request::is(ltrim($link['href'], '/')) ? 'font-medium  bg-orange-50 text-primary-orange' : '' }}"
                       @click="mobileMenuOpen = false">
                        {{ $link['title'] }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Overlay del modal -->
    <div x-show="showModal" x-transition.opacity x-cloak class="fixed inset-0 bg-black/50 z-50" @click="closeModal()"></div>
</div>
