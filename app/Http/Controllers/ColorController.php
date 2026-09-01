<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $query = Color::query()->orderBy('name',  'asc');

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
        }

        $colores = $query->paginate($perPage);

        return inertia('admin/coloresAdmin', [
            'colores' => $colores,
        ]);
    }





    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'hex' => 'required|string|max:7',
        ]);

        Color::create($data);

        return redirect()->back()->with('success', 'Color creado exitosamente.');
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $data = $request->validate([

            'name' => 'sometimes|nullable|string|max:255',
            'hex' => 'required|string|max:7',
        ]);

        $color = Color::findOrFail($request->id);
        $color->update($data);

        return redirect()->back()->with('success', 'Color actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $color = Color::findOrFail($request->id);
        $color->delete();

        return redirect()->back()->with('success', 'Color eliminado exitosamente.');
    }
}
