<?php

namespace App\Http\Controllers;

use App\Models\Recursos;
use Illuminate\Http\Request;

class RecursosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recursos = Recursos::first();
        return inertia('admin/recursosAdmin', ['recursos' => $recursos]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'title_es' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'text_es' => 'nullable|string',
            'text_en' => 'nullable|string',
            'archivo_fotos' => 'sometimes|nullable|file',
            'archivo_cad' => 'sometimes|nullable|file',
        ]);

        if ($request->hasFile('archivo_fotos')) {
            $data['archivo_fotos'] = $request->file('archivo_fotos')->store('images', 'public');
        }

        if ($request->hasFile('archivo_cad')) {
            $data['archivo_cad'] = $request->file('archivo_cad')->store('images', 'public');
        }

        Recursos::updateOrCreate(['id' => 1], $data);
    }
}
