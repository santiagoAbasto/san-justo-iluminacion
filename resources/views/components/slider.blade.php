<div class="overflow-hidden min-h-[768px] max-lg:min-h-[600px] max-md:min-h-[500px] max-sm:min-h-[500px]">
    <div
        class="slider-track h-[768px] max-lg:h-[600px] max-md:h-[500px] max-sm:h-[400px] flex transition-transform duration-500 ease-in-out justify-center">
        @foreach ($sliders as $slider)
            @php $ext = pathinfo($slider->media, PATHINFO_EXTENSION); @endphp
            <div class="slider-item min-w-full relative h-[768px] max-lg:h-[600px] max-md:h-[500px] max-sm:h-[500px]">
                <div class="absolute inset-0 bg-black z-0">
                    @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                        <img src="{{ asset($slider->media) }}" alt="Slider Image"
                            class="w-full h-full object-cover max-sm:object-center" data-duration="6000">
                    @elseif (in_array($ext, ['mp4', 'webm', 'ogg']))
                        <video class="w-full h-full object-cover object-center max-sm:object-center" autoplay muted
                            onended="nextSlide()">
                            <source src="{{ asset($slider->media) }}" type="video/{{ $ext }}">
                            {{ __(key: 'Tu navegador no soporta el formato de video.') }}
                        </video>
                    @endif
                </div>
                <div class="absolute inset-0 bg-black/50 max-sm:bg-black/60 z-10"></div>
                <div
                    class="absolute inset-0 flex z-20 lg:max-w-[1200px] max-lg:max-w-[1000px] max-md:max-w-[768px] lg:mx-auto max-lg:mx-auto max-md:mx-auto max-sm:px-4 max-md:px-6 max-lg:px-8">
                    <div
                        class="relative flex flex-col gap-4 sm:gap-6 lg:gap-19 max-md:gap-4 max-sm:gap-3 w-full justify-center items-center">
                        <div
                            class="w-fit items-center text-center justify-center max-sm:max-w-full text-white flex flex-col gap-8 max-lg:gap-6 max-md:gap-5 max-sm:gap-4">

                            <h1
                                class="text-[32px] max-lg:text-[28px] max-md:text-[24px] max-sm:text-[20px] font-bold w-fit font-custom! leading-none max-sm:leading-tight">
                                {{ $slider->title }}
                            </h1>
                            <h3
                                class="text-[20px] max-lg:text-[18px] max-md:text-[16px] max-sm:text-[14px] max-w-[524px] max-lg:max-w-[450px] max-md:max-w-[400px] max-sm:max-w-full max-sm:px-2 leading-relaxed max-sm:leading-normal">
                                {{$slider->subtitle}}
                            </h3>

                            <a href="{{ $slider->link}}"
                                class="flex justify-center items-center w-[280px] max-lg:w-[260px] max-md:w-[240px] max-sm:w-full max-sm:max-w-[280px] h-[42px] max-sm:h-[44px] bg-primary-orange rounded-sm font-medium text-[14px] max-sm:text-[13px] hover:bg-orange-600 transition-colors duration-300">
                                {{__('CONOCE NUESTROS PRODUCTOS')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <!-- Slider Navigation Dots -->

</div>

<!-- Slider JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sliderTrack = document.querySelector('.slider-track');
        const sliderItems = document.querySelectorAll('.slider-item');
        const dots = document.querySelectorAll('.dot');
        let currentIndex = 0,
            autoSlideTimeout, isTransitioning = false;

        window.nextSlide = () => {
            if (isTransitioning) return;
            clearTimeout(autoSlideTimeout);
            currentIndex = (currentIndex + 1) % sliderItems.length;
            updateSlider();
        };

        window.goToSlide = i => {
            if (isTransitioning || i === currentIndex) return;
            clearTimeout(autoSlideTimeout);
            currentIndex = i;
            updateSlider();
        };

        function updateSlider() {
            isTransitioning = true;
            sliderItems.forEach(item => item.querySelector('video')?.pause());
            sliderTrack.style.transform = `translateX(-${currentIndex * 100}%)`;
            dots.forEach((dot, i) => dot.classList.toggle('opacity-90', i === currentIndex) || dot.classList
                .toggle('opacity-50', i !== currentIndex));
            scheduleNextSlide();
            setTimeout(() => isTransitioning = false, 500);
        }

        function scheduleNextSlide() {
            clearTimeout(autoSlideTimeout);
            const slide = sliderItems[currentIndex],
                video = slide.querySelector('video'),
                img = slide.querySelector('img');
            if (video) {
                video.currentTime = 0;
                video.play();
            } else autoSlideTimeout = setTimeout(window.nextSlide, img?.dataset.duration ? +img.dataset
                .duration : 6000);
        }

        sliderItems.forEach(item => item.querySelector('video') && (item.querySelector('video').onended = window
            .nextSlide));
        updateSlider();

        // Touch/swipe functionality for mobile
        let startX = 0;
        let startY = 0;
        let currentX = 0;
        let currentY = 0;
        let isDragging = false;

        function handleTouchStart(e) {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
            isDragging = true;
        }

        function handleTouchMove(e) {
            if (!isDragging) return;
            e.preventDefault();
            currentX = e.touches[0].clientX;
            currentY = e.touches[0].clientY;
        }

        function handleTouchEnd(e) {
            if (!isDragging) return;
            isDragging = false;

            const diffX = startX - currentX;
            const diffY = startY - currentY;

            // Only trigger slide change if horizontal swipe is more significant than vertical
            if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                if (diffX > 0) {
                    // Swipe left - next slide
                    window.nextSlide();
                } else {
                    // Swipe right - previous slide
                    clearTimeout(autoSlideTimeout);
                    currentIndex = (currentIndex - 1 + sliderItems.length) % sliderItems.length;
                    updateSlider();
                }
            }
        }

        // Add touch event listeners
        if (sliderTrack) {
            sliderTrack.addEventListener('touchstart', handleTouchStart, { passive: false });
            sliderTrack.addEventListener('touchmove', handleTouchMove, { passive: false });
            sliderTrack.addEventListener('touchend', handleTouchEnd, { passive: false });
        }
    });
</script>