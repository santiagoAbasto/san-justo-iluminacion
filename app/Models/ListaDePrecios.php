<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ListaDePrecios extends Model
{
    protected $guarded = [];

    // Si el archivo se guarda como una ruta
    public function getArchivoAttribute($value)
    {
        return asset("storage/" . $value);
    }

    // En tu modelo ListaDePrecios
    protected $appends = ['formato', 'peso'];

    public function getFormatoAttribute()
    {
        return $this->getFormatoArchivo();
    }

    public function getPesoAttribute()
    {
        return $this->getPesoArchivo();
    }

    public function getFormatoArchivo()
    {
        // Usar getOriginal() para obtener el valor sin procesar por el accessor
        $archivoOriginal = $this->getOriginal('archivo');

        if (empty($archivoOriginal)) {
            return null;
        }

        // Obtener la extensión del archivo
        $extension = pathinfo($archivoOriginal, PATHINFO_EXTENSION);
        return $extension;
    }

    public function getPesoArchivo()
    {
        $archivoOriginal = $this->getOriginal('archivo');

        if (empty($archivoOriginal)) {
            return null;
        }

        // Si es una URL completa, extraer la ruta relativa
        if (filter_var($archivoOriginal, FILTER_VALIDATE_URL)) {
            // Extraer la parte después de '/storage/'
            $urlParts = parse_url($archivoOriginal);
            $path = $urlParts['path'];

            // Remover '/storage/' del inicio para obtener la ruta relativa
            if (strpos($path, '/storage/') === 0) {
                $archivoOriginal = substr($path, strlen('/storage/'));
            } else {
                // Fallback: usar solo el nombre del archivo
                $archivoOriginal = basename($archivoOriginal);
            }
        }

        // Lista de discos a verificar
        $discos = ['public', 'local'];

        foreach ($discos as $nombreDisco) {
            if (Storage::disk($nombreDisco)->exists($archivoOriginal)) {
                try {
                    $bytes = Storage::disk($nombreDisco)->size($archivoOriginal);

                    // Convertir bytes a formato legible
                    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
                    $bytes = max($bytes, 0);
                    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
                    $pow = min($pow, count($units) - 1);

                    $bytes /= (1 << (10 * $pow));

                    return round($bytes, 2) . ' ' . $units[$pow];
                } catch (\Exception $e) {
                    Log::error("Error al obtener tamaño del archivo: " . $e->getMessage());
                    continue;
                }
            }
        }

        // Si no se encuentra, hacer log para debug
        Log::warning("Archivo no encontrado después de procesar URL: {$archivoOriginal}");
        return null;
    }

    // Método adicional para obtener solo la ruta del archivo sin la URL completa
    public function getRutaArchivoAttribute()
    {
        return $this->getOriginal('archivo');
    }

    public function productos()
    {
        return $this->hasMany(ListaProductos::class, 'lista_de_precios_id');
    }

    public function clientes()
    {
        return $this->hasMany(User::class, 'lista_de_precios_id');
    }
}
