<div class="mx-auto flex w-[1200px] max-sm:w-full max-sm:px-4 flex-col gap-5 my-10 max-sm:my-6">
    <div class="flex flex-row  max-sm:gap-3 items-center justify-between">
        <h2 class="text-[32px] max-sm:text-[24px] font-semibold">Productos destacados</h2>
        <a href="{{ url('/productos') }}"
            class="text-primary-orange border-primary-orange hover:bg-primary-orange flex h-[41px] max-sm:h-[36px] w-[127px] max-sm:w-[100px] items-center justify-center border text-base max-sm:text-sm font-semibold transition duration-300 rounded-sm hover:text-white">
            Ver todos
        </a>
    </div>

    <div class="grid grid-cols-4 max-sm:grid-cols-1 gap-5 max-sm:gap-4">
        @foreach ($productos as $producto)
            <a href="{{ "/p/" . $producto->code }}"
                class=" transition transform hover:-translate-y-1 hover:shadow-lg duration-300
                                                                                                                                            h-[420px]  flex flex-col w-[288px] max-sm:w-full rounded-sm border border-[#DEDFE0]">
                <div class="h-full flex flex-col">
                    @if ($producto->imagenes->count() > 0)
                        <div class="relative min-h-[287px] max-sm:h-[200px]">
                            <img src="{{ $producto->imagenes->first()->image }}" alt="{{ $producto->name }}"
                                class=" w-full h-full  object-contain rounded-t-sm">
                            <h2 class="absolute left-3 bottom-2 text-[14px] font-semibold uppercase text-primary-orange">
                                {{$producto->categoria->name}}
                            </h2>
                        </div>

                    @else
                        <div
                            class="w-full min-h-[243px] max-sm:min-h-[200px] bg-gray-100 flex items-center justify-center text-gray-500 ">
                            <span>Sin imagen</span>
                        </div>
                    @endif
                    <div class="h-1 bg-[#DEDFE0] mx-3"></div>
                    <div class="flex flex-col justify-evenly h-full max-sm:p-3 px-3">
                        <div class="flex flex-row justify-between">
                            <h3
                                class="text-black group-hover:text-green-700 text-[16px] max-sm:text-[14px] transition-colors duration-300">
                                Cod. Or.: {{ $producto->code }}
                            </h3>
                            <h3
                                class="text-primary-orange group-hover:text-green-700 text-[16px] max-sm:text-[14px] transition-colors duration-300">
                                Cod. SR: {{ $producto->code_sr }}
                            </h3>
                        </div>
                        <p
                            class="text-gray-800 text-[18px] max-sm:text-[14px] font-semibold transition-colors duration-300 ">
                            {{ $producto->name }}
                        </p>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>