<?php

namespace App\Http\Controllers;

use App\Models\DondeComprarContenido;
use Illuminate\Http\Request;

class DondeComprarContenidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contenido = DondeComprarContenido::first();
        return inertia('admin/dondeComprarContenido', ['dondeComprarContenido' => $contenido]);
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
        ]);

        DondeComprarContenido::updateOrCreate(['id' => 1], $data);
    }
}
