<?php

namespace App\Http\Controllers;

use App\Mail\TrabajaConNosotrosMail;
use App\Models\TrabajaConNosotros;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TrabajaConNosotrosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trabaja = TrabajaConNosotros::first();
        return inertia('admin/trabajaConNosotros', ['trabaja' => $trabaja]);
    }

    public function mandarMail(Request $request)
    {
        try {
            $data = $request->validate([
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'telefono' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'archivo' => 'required|file',
            ]);

            Mail::to(TrabajaConNosotros::first()->email)->send(new TrabajaConNosotrosMail($data, $request->file('archivo')));

            return response()->json([
                'success' => true,
                'message' => 'Formulario enviado exitosamente'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
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
            'email' => 'nullable|email|max:255',
        ]);

        $trabaja = TrabajaConNosotros::first();

        if ($trabaja) {
            $trabaja->update($data);
        } else {
            TrabajaConNosotros::create($data);
        }

        return redirect()->back()->with('success', 'Información guardada correctamente.');
    }
}
