<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Modelo;
use Illuminate\Http\Request;

class ModeloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $query = Modelo::query()->with('marca')->orderBy('order', 'asc');

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
        }

        $modelos = $query->paginate($perPage);


        $marcas = Marca::orderBy('order', 'asc')->get();

        return inertia(
            'admin/modelosAdmin',
            [
                'modelos' => $modelos,
                'marcas' => $marcas,
            ]
        );
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'order' => 'nullable|string|max:255',
            'marca_id' => 'nullable|exists:marcas,id',
        ]);

        Modelo::create($data);

        return redirect()->back()->with('success', 'Modelo creado exitosamente.');
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $modelo = Modelo::findOrFail($request->id);
        if (!$modelo) {
            return redirect()->back()->with('error', 'No se encontró el modelo.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'order' => 'nullable|string|max:255',
            'marca_id' => 'nullable|exists:marcas,id',
        ]);

        $modelo->update($data);

        return redirect()->back()->with('success', 'Modelo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $modelo = Modelo::findOrFail($request->id);
        if (!$modelo) {
            return redirect()->back()->with('error', 'No se encontró el modelo.');
        }

        $modelo->delete();

        return redirect()->back()->with('success', 'Modelo eliminado exitosamente.');
    }
}
