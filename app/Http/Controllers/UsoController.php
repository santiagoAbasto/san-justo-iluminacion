<?php

namespace App\Http\Controllers;

use App\Models\Espacio;
use App\Models\Uso;
use Illuminate\Http\Request;

class UsoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $query = Uso::query()->orderBy('order', direction: 'asc')->with('espacio');

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
        }

        $usos = $query->paginate($perPage);
        $espacios = Espacio::orderBy('order', 'asc')->get();

        return inertia('admin/usosAdmin', [
            'usos' => $usos,
            'espacios' => $espacios,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name_es' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'order' => 'nullable|sometimes|string',
            'espacio_id' => 'required|exists:espacios,id',
        ]);

        Uso::create($validatedData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name_es' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'order' => 'nullable|sometimes|string',
            'espacio_id' => 'required|exists:espacios,id',
        ]);

        Uso::findOrFail($request->id)->update($validatedData);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Uso::firstOrFail($request->id)->delete();
    }
}
