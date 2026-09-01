<!-- resources/views/layouts/default.blade.php -->

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'San Justo Iluminación')</title>
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

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <meta name="description" content="@yield('description', 'San Justo Iluminación') ">
    <meta name="keywords"
        content="@yield('keywords', 'iluminación, lámparas, artefactos, San Justo Iluminación')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="font-sans text-gray-900 antialiased">
    <!-- Bootstrap JS -->


    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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
