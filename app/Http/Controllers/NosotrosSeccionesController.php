<?php

namespace App\Http\Controllers;

use App\Models\NosotrosSecciones;
use Illuminate\Http\Request;

class NosotrosSeccionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $secciones = NosotrosSecciones::orderBy('order')->get();
        return inertia('admin/nosotrosSecciones', ['secciones' => $secciones]);
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

        $seccion = NosotrosSecciones::create($validated);
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

        $seccion = NosotrosSecciones::find($request->id);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $validated['image'] = $path;
        }

        $seccion->update($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $seccion = NosotrosSecciones::find($request->id);
        if ($seccion) {
            $seccion->delete();
        }
    }
}
