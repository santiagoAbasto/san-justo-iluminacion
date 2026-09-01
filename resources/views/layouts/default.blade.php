<!-- resources/views/layouts/default.blade.php -->

<!DOCTYPE html>
<html lang="{{ request('lang') === 'en' ? 'en' : 'es' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $seoTitle = trim($__env->yieldContent('title', 'San Justo Iluminación'));
        $seoDescription = trim($__env->yieldContent(
            'description',
            'Diseño y fabricación de artefactos de iluminación para hogares, comercios y proyectos.'
        ));
        $seoBaseUrl = url()->current();
        $seoCanonical = trim($__env->yieldContent(
            'canonical',
            request('lang') === 'en' ? $seoBaseUrl . '?lang=en' : $seoBaseUrl
        ));
        $seoImage = trim($__env->yieldContent('seo_image', $logos->logo_principal ?? ''));
        $seoHasFilterQuery = request()->collect()->except(['lang'])->isNotEmpty();
        $seoDefaultRobots = request()->is('buscar', 'busqueda', 'login', 'pedidos/login.php') || $seoHasFilterQuery
            ? 'noindex, nofollow, noarchive'
            : 'index, follow, max-image-preview:large';
        $seoRobots = trim($__env->yieldContent('robots', $seoDefaultRobots));
    @endphp
    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="{{ $seoRobots }}">
    <link rel="canonical" href="{{ $seoCanonical }}">
    <link rel="alternate" hreflang="es-AR" href="{{ $seoBaseUrl }}">
    <link rel="alternate" hreflang="en" href="{{ $seoBaseUrl }}?lang=en">
    <link rel="alternate" hreflang="x-default" href="{{ $seoBaseUrl }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="San Justo Iluminación">
    <meta property="og:locale" content="{{ request('lang') === 'en' ? 'en_US' : 'es_AR' }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $seoCanonical }}">
    @if ($seoImage)
        <meta property="og:image" content="{{ $seoImage }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    @if ($seoImage)
        <meta name="twitter:image" content="{{ $seoImage }}">
    @endif
    @vite(['resources/css/app.css'])
    @stack('head') {{-- Para inyectar scripts o estilos específicos --}}
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>


    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <meta name="keywords"
        content="@yield('keywords', 'iluminación, lámparas, artefactos, San Justo Iluminación')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script type="application/ld+json">
        @json([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'San Justo Iluminación',
            'url' => url('/'),
            'logo' => $logos->logo_principal ?? null,
            'sameAs' => [
                'https://www.facebook.com/sanjustoiluminacion/',
                'https://www.instagram.com/sanjusto_iluminacion/',
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    </script>
</head>

<body class="font-sans text-gray-900 antialiased">
    <!-- Bootstrap JS -->


    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Contenido principal --}}

    @yield('content')


    {{-- Footer (opcional) --}}
    @includeIf('components.footer')

    {{-- resources/views/components/whatsapp.blade.php --}}
    @if(isset($contacto['wp']) && !empty($contacto['wp']))
        <a target="_blank" rel="noopener noreferrer"
            href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contacto['wp']) }}" class="fixed right-0 bottom-0">
            <img src="{{ asset('images/wpIcon.png') }}" alt="WhatsApp" />
        </a>
    @endif

    @stack('scripts') {{-- Scripts específicos de cada vista --}}
</body>

</html>
