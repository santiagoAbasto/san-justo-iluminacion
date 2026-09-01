<?php

namespace App\Http\Controllers;

use App\Models\Novedades;
use App\Models\Producto;
use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SeoController extends Controller
{
    public function sitemap(Request $request)
    {
        $sitemap = Sitemap::create();

        foreach ([
            route('home'),
            route('productos'),
            route('nosotros'),
            route('trabaja.con.nosotros'),
            route('comercio.exterior'),
            route('recursos'),
            route('calidad'),
            route('contacto'),
            route('novedades'),
            route('donde-comprar.index'),
        ] as $publicUrl) {
            $sitemap->add(Url::create($publicUrl));
        }

        Producto::query()
            ->whereNotNull('code')
            ->select(['id', 'code', 'updated_at'])
            ->orderBy('id')
            ->chunkById(500, function ($productos) use ($sitemap) {
                foreach ($productos as $producto) {
                    $url = Url::create(route('producto.show', $producto->code));

                    if ($producto->updated_at) {
                        $url->setLastModificationDate($producto->updated_at);
                    }

                    $sitemap->add($url);
                }
            });

        Novedades::query()
            ->select(['id', 'updated_at'])
            ->orderBy('id')
            ->chunkById(500, function ($novedades) use ($sitemap) {
                foreach ($novedades as $novedad) {
                    $url = Url::create(route('novedades.show', $novedad->id));

                    if ($novedad->updated_at) {
                        $url->setLastModificationDate($novedad->updated_at);
                    }

                    $sitemap->add($url);
                }
            });

        return $sitemap->toResponse($request);
    }
}
