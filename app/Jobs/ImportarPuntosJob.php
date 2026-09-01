<?php

namespace App\Jobs;

use App\Models\PuntoVenta;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ImportarPuntosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $archivoPath;

    public function __construct($archivoPath)
    {
        $this->archivoPath = $archivoPath;
    }

    public function handle()
    {
        $fullPath = Storage::path($this->archivoPath);

        $rows = Excel::toArray([], $fullPath)[0]; // primera hoja

        // Saltar encabezado
        foreach (array_slice($rows, 1) as $row) {
            try {
                PuntoVenta::updateOrCreate(
                    ['nombre' => $row[0]], // criterio de unicidad
                    [
                        'direccion'   => $row[1] ?? '',
                        'provincia'   => $row[2] ?? '',
                        'localidad'   => $row[3] ?? '',
                        'telefono'    => $row[4] ?? null,
                        'email'       => $row[5] ?? null,
                        'latitud'     => $row[6] ?? 0,
                        'longitud'    => $row[7] ?? 0,
                        'descripcion' => null,
                        'activo'      => true,
                    ]
                );
            } catch (\Exception $e) {
                Log::error("Error importando punto de venta: " . $e->getMessage());
            }
        }
    }
}
