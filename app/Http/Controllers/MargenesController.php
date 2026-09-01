<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MargenesController extends Controller
{
    public function index()
    {
        $tipo_de_productos = Categoria::all(); // Ajusta según tu modelo

        // Obtener márgenes de la sesión
        $margenes = session('margenes', [
            'general' => 0,
            'tipos' => []
        ]);

        return Inertia::render('privada/margenes', [
            'tipo_de_productos' => $tipo_de_productos,
            'margenes' => $margenes
        ]);
    }

    public function guardarMargenes(Request $request)
    {
        $request->validate([
            'margenes.general' => 'required|numeric|min:0|max:100',
            'margenes.tipos' => 'array',
            'margenes.tipos.*' => 'numeric|min:0|max:100'
        ]);

        // Guardar todos los márgenes en la sesión
        session(['margenes' => $request->margenes]);

        return back()->with('success', 'Márgenes guardados correctamente');
    }

    // Método para obtener márgenes desde otras partes de la aplicación
    public function obtenerMargenes()
    {
        return session('margenes', [
            'general' => 0,
            'tipos' => []
        ]);
    }
}
