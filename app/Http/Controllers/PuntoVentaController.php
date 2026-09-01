<?php

namespace App\Http\Controllers;

use App\Models\DondeComprarContenido;
use App\Models\Provincia;
use App\Models\PuntoVenta;
use Illuminate\Http\Request;

class PuntoVentaController extends Controller
{
    // Vista pública del mapa
    public function index(Request $request)
    {
        $provincia = $request->get('provincia');
        $localidad = $request->get('localidad');

        $puntosVenta = PuntoVenta::activos()
            ->porProvincia($provincia)
            ->porLocalidad($localidad)
            ->get();

        $provincias = Provincia::orderBy('name')->with('localidades')->get();
        $contenido = DondeComprarContenido::first();

        $localidades = [];
        if ($provincia) {
            $provinciaObj = Provincia::where('name', $provincia)->with('localidades')->first();
            $localidades = $provinciaObj ? $provinciaObj->localidades : [];
        }

        return view('donde-comprar', compact('puntosVenta', 'provincias', 'localidades', 'provincia', 'localidad', 'contenido'));
    }

    // API para obtener puntos de venta (AJAX)
    public function api(Request $request)
    {
        $provincia = $request->get('provincia');
        $localidad = $request->get('localidad');
        $nombre = $request->get('nombre');

        $query = PuntoVenta::activos();

        if ($provincia) {
            $query->porProvincia($provincia);
        }

        if ($localidad) {
            $query->porLocalidad($localidad);
        }

        if ($nombre) {
            $query->where('nombre', $nombre);
        }

        $puntosVenta = $query->get();

        return response()->json($puntosVenta);
    }

    // API para obtener localidades por provincia
    public function getLocalidades(Request $request)
    {
        $provincia = $request->get('provincia');

        if (!$provincia) {
            return response()->json([]);
        }

        // Obtener localidades desde la tabla de provincias/localidades
        $provinciaObj = Provincia::where('name', $provincia)->with('localidades')->first();
        $localidadesDB = $provinciaObj ? $provinciaObj->localidades : collect([]);

        // También obtener localidades únicas desde los puntos de venta
        $localidadesPuntos = PuntoVenta::where('provincia', $provincia)
            ->whereNotNull('localidad')
            ->distinct()
            ->pluck('localidad')
            ->filter()
            ->map(function ($nombre) {
                return ['name' => $nombre];
            });

        // Combinar y eliminar duplicados
        $todasLocalidades = $localidadesDB->concat($localidadesPuntos)
            ->unique('name')
            ->sortBy('name')
            ->values();

        return response()->json($todasLocalidades);
    }

    public function indexAdmin(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $query = PuntoVenta::query()->orderBy('nombre',  'asc');

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('nombre', 'LIKE', '%' . $searchTerm . '%');
        }

        $puntosVenta = $query->paginate($perPage);

        $provincias = Provincia::orderBy('name')->with('localidades')->get();
        return inertia('admin/puntosVenta', ['puntosVenta' => $puntosVenta, 'provincias' => $provincias]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|sometimes|string|max:255',
            'direccion' => 'nullable|sometimes|string|max:255',
            'provincia' => 'nullable|sometimes|string|max:255',
            'localidad' => 'nullable|sometimes|string|max:255',
            'latitud' => 'nullable|sometimes|numeric',
            'longitud' => 'nullable|sometimes|numeric',
            'telefono' => 'nullable|sometimes|string|max:50',
            'email' => 'nullable|sometimes|email|max:255',
            'activo' => 'sometimes|boolean',
        ]);
        PuntoVenta::create($data);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|sometimes|string|max:255',
            'direccion' => 'nullable|sometimes|string|max:255',
            'provincia' => 'nullable|sometimes|string|max:255',
            'localidad' => 'nullable|sometimes|string|max:255',
            'latitud' => 'nullable|sometimes|numeric',
            'longitud' => 'nullable|sometimes|numeric',
            'telefono' => 'nullable|sometimes|string|max:50',
            'email' => 'nullable|sometimes|email|max:255',
            'activo' => 'sometimes|boolean',
        ]);
        $puntoVenta = PuntoVenta::find($request->id);
        $puntoVenta->update($data);
    }

    public function destroy(Request $request)
    {
        $puntoVenta = PuntoVenta::find($request->id);
        if ($puntoVenta) {
            $puntoVenta->delete();
        }
    }
}
