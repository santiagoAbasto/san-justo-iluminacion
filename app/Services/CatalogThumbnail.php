<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use RuntimeException;

class CatalogThumbnail
{
    private const MAX_DIMENSION = 640;

    public function url(string $image): string
    {
        $thumbnail = $this->thumbnailPath($image);

        return asset('storage/'.($thumbnail && Storage::disk('public')->exists($thumbnail) ? $thumbnail : $image));
    }

    public function thumbnailPath(string $image): ?string
    {
        $source = $this->sourcePath($image);

        if (! $source) {
            return null;
        }

        // Una nueva versión del original usa otra URL, incluso con caché immutable.
        $version = hash('sha256', $image.'|'.filemtime($source).'|'.filesize($source).'|640-webp-v1');

        return 'catalogo/miniaturas/'.$version.'.webp';
    }

    public function generate(string $image): ?string
    {
        $source = $this->sourcePath($image);
        $thumbnail = $this->thumbnailPath($image);

        if (! $source || ! $thumbnail) {
            return null;
        }

        $disk = Storage::disk('public');

        if ($disk->exists($thumbnail)) {
            return $thumbnail;
        }

        $size = @getimagesize($source);

        if (! $size || $size[0] * $size[1] > 20000000) {
            throw new RuntimeException('Imagen inválida o demasiado grande para generar la miniatura: '.$image);
        }

        $original = match ($size[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($source),
            IMAGETYPE_PNG => @imagecreatefrompng($source),
            IMAGETYPE_WEBP => @imagecreatefromwebp($source),
            default => false,
        };

        if (! $original) {
            throw new RuntimeException('Formato de imagen no compatible: '.$image);
        }

        $scale = min(1, self::MAX_DIMENSION / max($size[0], $size[1]));
        $width = max(1, (int) round($size[0] * $scale));
        $height = max(1, (int) round($size[1] * $scale));
        $resized = imagecreatetruecolor($width, $height);
        $temporary = null;

        try {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagefill($resized, 0, 0, imagecolorallocatealpha($resized, 0, 0, 0, 127));
            imagecopyresampled($resized, $original, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);

            $destination = $disk->path($thumbnail);
            $directory = dirname($destination);

            if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
                throw new RuntimeException('No se pudo crear el directorio de miniaturas.');
            }

            $temporary = tempnam($directory, 'thumbnail-');

            if (! $temporary || ! imagewebp($resized, $temporary, 82) || filesize($temporary) === 0) {
                throw new RuntimeException('No se pudo guardar la miniatura: '.$image);
            }

            chmod($temporary, 0644);

            if (! rename($temporary, $destination)) {
                throw new RuntimeException('No se pudo publicar la miniatura: '.$image);
            }

            return $thumbnail;
        } finally {
            imagedestroy($original);
            imagedestroy($resized);

            if ($temporary && is_file($temporary)) {
                unlink($temporary);
            }
        }
    }

    private function sourcePath(string $image): ?string
    {
        $root = realpath(Storage::disk('public')->path(''));
        $source = realpath(Storage::disk('public')->path($image));

        return $root && $source && str_starts_with($source, $root.DIRECTORY_SEPARATOR) && is_file($source)
            ? $source : null;
    }
}
