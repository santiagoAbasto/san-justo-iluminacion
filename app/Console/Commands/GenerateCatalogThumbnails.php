<?php

namespace App\Console\Commands;

use App\Models\ImagenProducto;
use App\Services\CatalogThumbnail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GenerateCatalogThumbnails extends Command
{
    protected $signature = 'productos:miniaturas';

    protected $description = 'Genera imágenes WebP livianas para el catálogo sin modificar los originales';

    public function handle(CatalogThumbnail $thumbnails): int
    {
        if (! extension_loaded('gd') || ! function_exists('imagewebp')) {
            $this->error('PHP necesita la extensión GD con soporte WebP para generar las miniaturas.');

            return self::FAILURE;
        }

        $generated = 0;
        $existing = 0;
        $missing = 0;
        $failed = 0;

        ImagenProducto::select(['id', 'image'])->chunkById(100, function ($images) use ($thumbnails, &$generated, &$existing, &$missing, &$failed) {
            foreach ($images as $image) {
                $path = $image->getRawOriginal('image') ?? '';

                try {
                    $thumbnail = $thumbnails->thumbnailPath($path);

                    if (! $thumbnail) {
                        $missing++;
                    } elseif (Storage::disk('public')->exists($thumbnail)) {
                        $existing++;
                    } else {
                        $thumbnails->generate($path);
                        $generated++;
                    }
                } catch (Throwable $exception) {
                    $failed++;
                    $this->warn($exception->getMessage());
                }
            }

            $this->line('Procesadas: '.($generated + $existing + $missing + $failed));
        });

        $this->info("Generadas: {$generated}. Existentes: {$existing}. Originales ausentes: {$missing}. Errores: {$failed}.");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
