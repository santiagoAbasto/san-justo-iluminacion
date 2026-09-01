<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $query = Cliente::query()->orderBy('order', direction: 'asc');


        $clientes = $query->paginate($perPage);
        return inertia('admin/clientesAdmin', ['clientes' => $clientes]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'order' => 'nullable|sometimes',
            'image' => 'required|file', // Máximo 2MB
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $data['image'] = $path;
        }

        Cliente::create($data);

        return redirect()->back()->with('success', 'Cliente creado exitosamente.');
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'order' => 'nullable|sometimes',
            'image' => 'nullable|file', // Máximo 2MB
        ]);

        $cliente = Cliente::findOrFail($request->id);

        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior si existe
            if ($cliente->image) {
                Storage::delete($cliente->image);
            }
            $path = $request->file('image')->store('images', 'public');
            $data['image'] = $path;
        }

        $cliente->update($data);

        return redirect()->back()->with('success', 'Cliente actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $cliente = Cliente::findOrFail($request->id);

        // Eliminar la imagen si existe
        if ($cliente->image) {
            Storage::delete($cliente->image);
        }

        $cliente->delete();

        return redirect()->back()->with('success', 'Cliente eliminado exitosamente.');
    }
}
