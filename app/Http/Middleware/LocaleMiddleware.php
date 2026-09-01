<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Obtener el idioma del parámetro, sesión o por defecto
        $locale = $request->get('lang') ?? session('locale', 'es');

        // Validar que el idioma sea válido
        if (!in_array($locale, ['en', 'es'])) {
            $locale = 'es';
        }

        // Establecer el idioma en la aplicación
        app()->setLocale($locale);

        // Guardar en sesión para persistencia
        session(['locale' => $locale]);

        return $next($request);
    }
}
