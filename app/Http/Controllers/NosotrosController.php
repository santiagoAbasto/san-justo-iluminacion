<?php

namespace App\Http\Controllers;

use App\Models\Nosotros;
use App\Models\NosotrosBanner;
use App\Models\Titulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class NosotrosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nosotros = Nosotros::first();



        return Inertia::render('admin/nosotrosAdmin', ['nosotros' => $nosotros]);
    }

    public function bannerView()
    {
        $nosotrosBanner = NosotrosBanner::first();
        return inertia('admin/nosotrosBanner', ['nosotrosBanner' => $nosotrosBanner]);
    }

    public function updateBanner(Request $request)
    {
        $data = $request->validate([
            'media' => 'nullable|sometimes|file',
        ]);

        if ($request->hasFile('media')) {
            $data['media'] = $request->file('media')->store('images', 'public');
        }

        $nosotrosBanner = NosotrosBanner::first();

        if ($nosotrosBanner) {
            $nosotrosBanner->update($data);
        } else {
            NosotrosBanner::create($data);
        }
        return redirect()->back()->with('success', 'Banner actualizado correctamente');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $data = $request->validate([

            'media_banner' => 'nullable|sometimes|file', // Máximo 2MB
            'title_seccion_uno_es' => 'nullable|sometimes|string|max:255',
            'title_seccion_uno_en' => 'nullable|sometimes|string|max:255',
            'text_seccion_uno_es' => 'nullable|sometimes|string',
            'text_seccion_uno_en' => 'nullable|sometimes|string',
            'title_seccion_dos_es' => 'nullable|sometimes|string|max:255',
            'title_seccion_dos_en' => 'nullable|sometimes|string|max:255',
            'text_seccion_dos_es' => 'nullable|sometimes|string',
            'text_seccion_dos_en' => 'nullable|sometimes|string',
            'title_seccion_tres_es' => 'nullable|sometimes|string|max:255',
            'title_seccion_tres_en' => 'nullable|sometimes|string|max:255',
            'text_seccion_tres_es' => 'nullable|sometimes|string',
            'text_seccion_tres_en' => 'nullable|sometimes|string',
            'image_seccion_dos' => 'nullable|sometimes|file', // Máximo 2MB
        ]);

        $dataTitulos = $request->validate([
            'title_espacios_es' => 'nullable|sometimes|string|max:255',
            'title_espacios_en' => 'nullable|sometimes|string|max:255',
            'title_catalogos_es' => 'nullable|sometimes|string|max:255',
            'title_catalogos_en' => 'nullable|sometimes|string|max:255',
            'title_lineas_es' => 'nullable|sometimes|string|max:255',
            'title_lineas_en' => 'nullable|sometimes|string|max:255',
            'title_marcas_es' => 'nullable|sometimes|string|max:255',
            'title_marcas_en' => 'nullable|sometimes|string|max:255',
        ]);

        // Procesar títulos por sección
        $secciones = ['espacios', 'catalogos', 'lineas', 'marcas'];

        foreach ($secciones as $seccion) {
            $titleEsKey = "title_{$seccion}_es";
            $titleEnKey = "title_{$seccion}_en";

            // Verificar si al menos uno de los campos de esta sección está presente
            if (isset($dataTitulos[$titleEsKey]) || isset($dataTitulos[$titleEnKey])) {
                // Buscar o crear el registro para esta sección
                $titulo = Titulo::firstOrNew(['seccion' => $seccion]);

                // Actualizar solo los campos que están presentes en la request
                if (isset($dataTitulos[$titleEsKey])) {
                    $titulo->title_es = $dataTitulos[$titleEsKey];
                }
                if (isset($dataTitulos[$titleEnKey])) {
                    $titulo->title_en = $dataTitulos[$titleEnKey];
                }

                $titulo->save();
            }
        }

        $nosotros = Nosotros::first();

        // Si no existe, lo creamos
        if (!$nosotros) {
            if ($request->hasFile('image_banner')) {
                $data['image_banner'] = $request->file('image_banner')->store('images', 'public');
            }
            if ($request->hasFile('image_seccion_uno')) {
                $data['image_seccion_uno'] = $request->file('image_seccion_uno')->store('images', 'public');
            }
            if ($request->hasFile('image_seccion_dos')) {
                $data['image_seccion_dos'] = $request->file('image_seccion_dos')->store('images', 'public');
            }

            Nosotros::create($data);

            return redirect()->back()->with('success', 'Banner creado correctamente');
        }

        // Si existe y se sube nueva imagen, eliminamos la anterior
        if ($request->hasFile('image_banner')) {
            if ($nosotros->getRawOriginal('image_banner')) {
                Storage::disk('public')->delete($nosotros->getRawOriginal('image_banner'));
            }
            $data['image_banner'] = $request->file('image_banner')->store('images', 'public');
        }
        if ($request->hasFile('image_seccion_uno')) {
            if ($nosotros->getRawOriginal('image_seccion_uno')) {
                Storage::disk('public')->delete($nosotros->getRawOriginal('image_seccion_uno'));
            }
            $data['image_seccion_uno'] = $request->file('image_seccion_uno')->store('images', 'public');
        }
        if ($request->hasFile('image_seccion_dos')) {
            if ($nosotros->getRawOriginal('image_seccion_dos')) {
                Storage::disk('public')->delete($nosotros->getRawOriginal('image_seccion_dos'));
            }
            $data['image_seccion_dos'] = $request->file('image_seccion_dos')->store('images', 'public');
        }

        $nosotros->update($data);

        return redirect()->back()->with('success', 'Banner actualizado correctamente');
    }
}
