<?php

namespace App\Http\Controllers;

use App\Models\ArchivoCalidad;
use App\Models\BannerPortada;
use App\Models\Calidad;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\ComercioExterior;
use App\Models\ComercioTarjetas;
use App\Models\Contacto;
use App\Models\Espacio;
use App\Models\Linea;
use App\Models\Marca;
use App\Models\Metadatos;
use App\Models\Modelo;
use App\Models\Nosotros;
use App\Models\NosotrosBanner;
use App\Models\NosotrosSecciones;
use App\Models\NosotrosTarjetas;
use App\Models\Novedades;
use App\Models\Producto;
use App\Models\Recursos;
use App\Models\Slider;
use App\Models\SubCategoria;
use App\Models\Titulo;
use App\Models\TrabajaConNosotros;
use App\Models\Valores;
use DragonCode\Contracts\Cashier\Resources\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomePages extends Controller
{
    public function home()
    {
        $metadatos = Metadatos::where('title', 'Inicio')->first();

        $categorias = Categoria::orderBy('order', 'asc')->get();
        $subcategorias = SubCategoria::orderBy('order', 'asc')->get();
        $homeInfo = BannerPortada::first();
        $novedades = Novedades::where('featured', true)->orderBy('order', 'asc')->get();

        $catalogos = ArchivoCalidad::orderBy('order', 'asc')->get();
        $espaciosHome = Espacio::where('destacado', true)->orderBy('order', 'asc')->with('usos')->get();
        $titulos = Titulo::orderBy('seccion')->get();
        $clientes = Cliente::orderBy('order', 'asc')->get();
        $lineas = Linea::orderBy('order', 'asc')->where('destacado', true)->get();



        return view('home', [
            'catalogos' => $catalogos,
            'homeInfo' => $homeInfo,
            'novedades' => $novedades,
            'categorias' => $categorias,
            'subcategorias' => $subcategorias,
            'metadatos' => $metadatos,
            'espaciosHome' => $espaciosHome,
            'titulos' => $titulos,
            'clientes' => $clientes,
            'lineas' => $lineas

        ]);
    }

    public function nosotros()
    {
        $metadatos = Metadatos::where('title', 'Nosotros')->first();
        $secciones = NosotrosSecciones::orderBy('order')->get();
        $tarjetas = NosotrosTarjetas::orderBy('order')->get();
        $banner = NosotrosBanner::first();
        return view('empresa', [
            'secciones' => $secciones,
            'tarjetas' => $tarjetas,
            'banner' => $banner,
            'metadatos' => $metadatos,
        ]);
    }

    public function trabajaConNosotros()
    {
        $metadatos = Metadatos::where('title', 'Trabaja con nosotros')->first();
        $trabaja = TrabajaConNosotros::first();
        return view('trabaja-con-nosotros', [
            'metadatos' => $metadatos,
            'trabaja' => $trabaja,
        ]);
    }

    public function comercioExterior()
    {
        $metadatos = Metadatos::where('title', 'Comercio Exterior')->first();
        $comercio = ComercioExterior::first();
        $tarjetas = ComercioTarjetas::orderBy('order')->get();
        $homeInfo = BannerPortada::first();
        $titulo = Titulo::where('seccion', 'marcas')->first();
        $clientes = Cliente::orderBy('order', 'asc')->get();
        return view('comercio-exterior', [
            'comercio' => $comercio,
            'metadatos' => $metadatos,
            'tarjetas' => $tarjetas,
            'homeInfo' => $homeInfo,
            'titulo' => $titulo,
            'clientes' => $clientes,
        ]);
    }

    public function recursos()
    {
        $metadatos = Metadatos::where('title', 'Recursos')->first();
        $recursos = Recursos::first();
        return view('recursos', [
            'recursos' => $recursos,
            'metadatos' => $metadatos,
        ]);
    }

    public function calidad()
    {
        $metadatos = Metadatos::where('title', 'Calidad')->first();
        $calidad = Calidad::first();
        $archivos = ArchivoCalidad::orderBy('order', 'asc')->get();

        return view('calidad', [
            'calidad' => $calidad,
            'archivos' => $archivos,
            'metadatos' => $metadatos,
        ]);
    }

    public function novedades()
    {
        $metadatos = Metadatos::where('title', 'Novedades')->first();
        $novedades = Novedades::orderBy('order', 'asc')
            ->get();
        return view('lanzamientos', [
            'novedades' => $novedades,
            'metadatos' => $metadatos,
        ]);
    }

    public function contacto(Request $request)
    {
        $metadatos = Metadatos::where('title', 'Contacto')->first();
        $contacto = Contacto::first();
        return view('contacto', [
            'contacto' => $contacto,
            'mensaje' => $request->mensaje ?? null,
            'metadatos' => $metadatos,
        ]);
    }
}
