<?php

namespace App\Http\Controllers;

use App\Models\Espacio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EspacioController extends Controller
{

    public function index(Request $request)
    {

        $perPage = $request->input('per_page', 10);

        $query = Espacio::query()->orderBy('order', direction: 'asc');

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
        }

        $espacios = $query->paginate($perPage);

        return inertia('admin/espaciosAdmin', [
            'espacios' => $espacios,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_es' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'image' => 'nullable|sometimes|file',
            'order' => 'nullable|sometimes|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('images', 'public');
        }

        Espacio::create($validated);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([

            'name_es' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'image' => 'nullable|sometimes|file',
            'order' => 'nullable|sometimes|string|max:255',
        ]);

        $espacio = Espacio::findOrFail($request->id);

        // si ya hay una imagen borrarla y poner la nueva
        if ($request->hasFile('image')) {
            // borrar la imagen anterior si existe
            if ($espacio->image) {
                Storage::disk('public')->delete($espacio->image);
            }
            $validated['image'] = $request->file('image')->store('images', 'public');
        }

        $espacio->update($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:espacios,id',
        ]);

        Espacio::destroy($validated['id']);
    }

    public function changeDestacado(Request $request)
    {
        $espacio = Espacio::findOrFail($request->id);

        // Check if the Espacio entry exists
        if (!$espacio) {
            return redirect()->back()->with('error', 'Espacio not found.');
        }

        // Toggle the featured status
        $espacio->destacado = !$espacio->destacado;
        $espacio->save();
    }
}
