<?php

namespace App\Http\Controllers;

use App\Models\ComercioTarjetas;
use App\Models\NosotrosSecciones;
use App\Models\NosotrosTarjetas;
use Illuminate\Http\Request;

class ComercioTarjetasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tarjetas = ComercioTarjetas::orderBy('order')->get();
        return inertia('admin/comercioTarjetas', ['tarjetas' => $tarjetas]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'order' => 'nullable|string',
            'name_es' => 'nullable|string',
            'name_en' => 'nullable|string',
            'text_es' => 'nullable|string',
            'text_en' => 'nullable|string',
            'image' => 'nullable|file',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $validated['image'] = $path;
        }

        $tarjeta = ComercioTarjetas::create($validated);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([

            'order' => 'nullable|string',
            'name_es' => 'nullable|string',
            'name_en' => 'nullable|string',
            'text_es' => 'nullable|string',
            'text_en' => 'nullable|string',
            'image' => 'nullable|file',
        ]);

        $tarjeta = ComercioTarjetas::find($request->id);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $validated['image'] = $path;
        }

        $tarjeta->update($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $tarjeta = ComercioTarjetas::find($request->id);
        if ($tarjeta) {
            $tarjeta->delete();
        }
    }
}
