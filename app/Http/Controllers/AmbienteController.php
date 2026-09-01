<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
use Illuminate\Http\Request;

class AmbienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $query = Ambiente::query()->orderBy('order', direction: 'asc');

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
        }

        $ambientes = $query->paginate($perPage);

        return inertia('admin/ambientesAdmin', [
            'ambientes' => $ambientes,
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name_es' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'order' => 'nullable|sometimes|string',
        ]);

        Ambiente::create($data);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name_es' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'order' => 'nullable|sometimes|string',
        ]);

        $ambiente = Ambiente::findOrFail($request->id);
        $ambiente->update($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $ambiente = Ambiente::findOrFail($request->id);
        $ambiente->delete();
    }
}
