<?php

namespace App\Http\Controllers;

use App\Models\BannerPortada;
use App\Models\Titulo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class BannerPortadaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banner = BannerPortada::first();
        $titulos = Titulo::orderBy('seccion')->get();



        return Inertia::render('admin/bannerPortadaAdmin', ['banner' => $banner, 'titulos' => $titulos]);
    }





    public function update(Request $request)
    {
        foreach (['custom_image_es', 'image_banner', 'image_seccion_uno', 'image_seccion_dos'] as $fileField) {
            if (!$request->hasFile($fileField)) {
                $request->request->remove($fileField);
            }
        }

        $data = $request->validate([
            'title_banner_es' => 'nullable|sometimes|string|max:255',
            'title_banner_en' => 'nullable|sometimes|string|max:255',
            'text_banner_es' => 'nullable|sometimes|string',
            'text_banner_en' => 'nullable|sometimes|string',
            'image_banner' => 'nullable|sometimes|file|mimes:jpg,jpeg,png,webp,gif,mp4,webm,ogg,mov,m4v',
            'title_seccion_uno_es' => 'nullable|sometimes|string|max:255',
            'title_seccion_uno_en' => 'nullable|sometimes|string|max:255',
            'text_seccion_uno_es' => 'nullable|sometimes|string',
            'text_seccion_uno_en' => 'nullable|sometimes|string',
            'image_seccion_uno' => 'nullable|sometimes|file|image',
            'title_seccion_dos_es' => 'nullable|sometimes|string|max:255',
            'title_seccion_dos_en' => 'nullable|sometimes|string|max:255',
            'text_seccion_dos_es' => 'nullable|sometimes|string',
            'text_seccion_dos_en' => 'nullable|sometimes|string',
            'title_seccion_tres_es' => 'nullable|sometimes|string|max:255',
            'title_seccion_tres_en' => 'nullable|sometimes|string|max:255',
            'text_seccion_tres_es' => 'nullable|sometimes|string',
            'text_seccion_tres_en' => 'nullable|sometimes|string',
            'image_seccion_dos' => 'nullable|sometimes|file|image',
            'custom_title_es' => 'nullable|sometimes|string|max:255',
            'custom_title_en' => 'nullable|sometimes|string|max:255',
            'custom_image_es' => 'nullable|sometimes|file|image',
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

        $bannerColumns = array_flip(Schema::getColumnListing((new BannerPortada)->getTable()));
        $data = array_intersect_key($data, $bannerColumns);

        $bannerPortada = BannerPortada::first();

        // Si no existe, lo creamos
        if (!$bannerPortada) {
            if ($request->hasFile('image_banner') && isset($bannerColumns['image_banner'])) {
                $data['image_banner'] = $request->file('image_banner')->store('images', 'public');
            }
            if ($request->hasFile('image_seccion_uno') && isset($bannerColumns['image_seccion_uno'])) {
                $data['image_seccion_uno'] = $request->file('image_seccion_uno')->store('images', 'public');
            }
            if ($request->hasFile('image_seccion_dos') && isset($bannerColumns['image_seccion_dos'])) {
                $data['image_seccion_dos'] = $request->file('image_seccion_dos')->store('images', 'public');
            }

            if ($request->hasFile('custom_image_es') && isset($bannerColumns['custom_image_es'])) {
                $data['custom_image_es'] = $request->file('custom_image_es')->store('images', 'public');
            }

            BannerPortada::create($data);

            return redirect()->back()->with('success', 'Banner creado correctamente');
        }

        // Si existe y se sube nueva imagen, eliminamos la anterior
        if ($request->hasFile('image_banner') && isset($bannerColumns['image_banner'])) {
            if ($bannerPortada->getRawOriginal('image_banner')) {
                Storage::disk('public')->delete($bannerPortada->getRawOriginal('image_banner'));
            }
            $data['image_banner'] = $request->file('image_banner')->store('images', 'public');
        }
        if ($request->hasFile('image_seccion_uno') && isset($bannerColumns['image_seccion_uno'])) {
            if ($bannerPortada->getRawOriginal('image_seccion_uno')) {
                Storage::disk('public')->delete($bannerPortada->getRawOriginal('image_seccion_uno'));
            }
            $data['image_seccion_uno'] = $request->file('image_seccion_uno')->store('images', 'public');
        }
        if ($request->hasFile('image_seccion_dos') && isset($bannerColumns['image_seccion_dos'])) {
            if ($bannerPortada->getRawOriginal('image_seccion_dos')) {
                Storage::disk('public')->delete($bannerPortada->getRawOriginal('image_seccion_dos'));
            }
            $data['image_seccion_dos'] = $request->file('image_seccion_dos')->store('images', 'public');
        }

        if ($request->hasFile('custom_image_es') && isset($bannerColumns['custom_image_es'])) {
            if ($bannerPortada->getRawOriginal('custom_image_es')) {
                Storage::disk('public')->delete($bannerPortada->getRawOriginal('custom_image_es'));
            }
            $data['custom_image_es'] = $request->file('custom_image_es')->store('images', 'public');
        }

        $bannerPortada->update($data);

        return redirect()->back()->with('success', 'Banner actualizado correctamente');
    }
}
