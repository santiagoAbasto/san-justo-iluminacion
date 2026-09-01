<?php

namespace App\Http\Controllers;

use App\Models\ComercioExterior;
use Illuminate\Http\Request;

class ComercioExteriorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comercio = ComercioExterior::first();
        return inertia('admin/comercioExterior', ['banner' => $comercio]);
    }



    public function update(Request $request)
    {
        $data = $request->validate([
            'title_seccion_uno_es' => 'nullable|string|max:255',
            'title_seccion_uno_en' => 'nullable|string|max:255',
            'text_seccion_uno_es' => 'nullable|string',
            'text_seccion_uno_en' => 'nullable|string',

            'title_seccion_dos_es' => 'nullable|string|max:255',
            'title_seccion_dos_en' => 'nullable|string|max:255',
            'text_seccion_dos_es' => 'nullable|string',
            'text_seccion_dos_en' => 'nullable|string',
            'image_seccion_dos' => 'sometimes|nullable|file',

            'title_seccion_tres_es' => 'nullable|string|max:255',
            'title_seccion_tres_en' => 'nullable|string|max:255',
            'text_seccion_tres_es' => 'nullable|string',
            'text_seccion_tres_en' => 'nullable|string',
            'image_seccion_tres' => 'sometimes|nullable|file',
            'image_seccion_tres_dos' => 'sometimes|nullable|file',
            'image_seccion_tres_tres' => 'sometimes|nullable|file',
        ]);

        if ($request->hasFile('image_seccion_dos')) {
            $data['image_seccion_dos'] = $request->file('image_seccion_dos')->store('images', 'public');
        }

        if ($request->hasFile('image_seccion_tres')) {
            $data['image_seccion_tres'] = $request->file('image_seccion_tres')->store('images', 'public');
        }

        if ($request->hasFile('image_seccion_tres_dos')) {
            $data['image_seccion_tres_dos'] = $request->file('image_seccion_tres_dos')->store('images', 'public');
        }

        if ($request->hasFile('image_seccion_tres_tres')) {
            $data['image_seccion_tres_tres'] = $request->file('image_seccion_tres_tres')->store('images', 'public');
        }

        ComercioExterior::updateOrCreate(['id' => 1], $data);

        return redirect()->back()->with('success', 'Comercio Exterior actualizado correctamente.');
    }
}
