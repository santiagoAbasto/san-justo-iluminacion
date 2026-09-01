@extends('layouts.default')

@section('title', 'Área de clientes | San Justo Iluminación')
@section('robots', 'noindex, nofollow, noarchive')

@section('content')
    <main class="min-h-[calc(100vh-100px)] bg-gray-50 px-4 py-16 sm:px-6 lg:px-8">
        <div class="mx-auto w-full max-w-md rounded-lg bg-white p-8 shadow-lg">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-semibold text-gray-900">Área de clientes</h1>
                <p class="mt-2 text-sm text-gray-600">Ingresa con tu usuario o correo electrónico.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="usuario" class="mb-2 block text-sm font-medium text-gray-700">Usuario o correo</label>
                    <input
                        id="usuario"
                        name="usuario"
                        type="text"
                        value="{{ old('usuario') }}"
                        required
                        autofocus
                        autocomplete="username"
                        class="w-full rounded-md border border-gray-300 px-4 py-3 outline-none transition focus:border-[#0049A0] focus:ring-2 focus:ring-[#0049A0]/20"
                    >
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-gray-700">Contraseña</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-md border border-gray-300 px-4 py-3 outline-none transition focus:border-[#0049A0] focus:ring-2 focus:ring-[#0049A0]/20"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full rounded-md bg-[#0049A0] px-4 py-3 font-semibold text-white transition hover:bg-[#003b82] focus:outline-none focus:ring-2 focus:ring-[#0049A0] focus:ring-offset-2"
                >
                    Ingresar
                </button>
            </form>

            <a href="{{ route('home') }}" class="mt-6 block text-center text-sm text-gray-600 hover:text-[#0049A0]">
                Volver al sitio
            </a>
        </div>
    </main>
@endsection
