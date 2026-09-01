<?php

namespace App\Jobs;

use App\Models\Ambiente;
use App\Models\Color;
use App\Models\Espacio;
use App\Models\ImagenProducto;
use App\Models\Linea;
use App\Models\LineaAmbiente;
use App\Models\ListaProductos;
use App\Models\Producto;
use App\Models\ProductoAmbiente;
use App\Models\ProductoColor;
use App\Models\Uso;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImportarProductosDesdeExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $archivoPath;

    public function __construct($archivoPath)
    {
        $this->archivoPath = $archivoPath;
    }

    public function handle()
    {
        $filePath = Storage::path($this->archivoPath);
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        Log::info('=== INICIO DEBUG EXCEL ===');
        Log::info('Total de filas: ' . count($rows));


        foreach ($rows as $index => $row) {
            Log::info("Fila {$index}: " . json_encode($row));

            if ($index === 0 || $row['N'] == "IMAGEN") {
                Log::info('Saltando encabezado');
                continue;
            }

            $espacio = trim($row['A']);
            $uso = trim($row['B']);
            $linea = trim($row['C']);
            $codigo = trim($row['D']);
            $ambiente_uno = trim($row['E']);
            $ambiente_dos = trim($row['F']);
            $ambiente_tres = trim($row['G']);
            $ambiente_cuatro = trim($row['H']);
            $ambiente_cinco = trim($row['I']);
            $ambiente_seis = trim($row['J']);
            $ambiente_siete = trim($row['K']);
            $ambiente_ocho = trim($row['L']);
            $ambiente_nueve = trim($row['M']);
            $origen = trim($row['P']);
            $nombre = trim($row['Q']);
            $colores = trim($row['R']);
            $lampara = trim($row['S']);
            $medidas = trim($row['T']);




            $ambiente_array = [
                $ambiente_uno,
                $ambiente_dos,
                $ambiente_tres,
                $ambiente_cuatro,
                $ambiente_cinco,
                $ambiente_seis,
                $ambiente_siete,
                $ambiente_ocho,
                $ambiente_nueve
            ];
            $ambientes = array_filter($ambiente_array, fn($value) => !is_null($value) && $value !== '');


            if ($espacio) {
                $espacio_nuevo = Espacio::updateOrCreate(
                    ['name_es' => $espacio]
                );

                if ($espacio_nuevo) {
                    $uso_nuevo = Uso::updateOrCreate(
                        ['name_es' => $uso],
                        ['name_es' => $uso, 'espacio_id' => $espacio_nuevo->id]
                    );
                }
            }

            if ($linea) {
                $linea_nueva = Linea::updateOrCreate(
                    ['name_es' => $linea],
                    ['name_es' => $linea]
                );
            }

            foreach ($ambientes as $ambiente) {
                $ambiente_nuevo = Ambiente::updateOrCreate(
                    ['name_es' => $ambiente],
                    ['name_es' => $ambiente]
                );

                if ($linea_nueva) {
                    LineaAmbiente::updateOrCreate(
                        ['linea_id' => $linea_nueva->id, 'ambiente_id' => $ambiente_nuevo->id],
                        ['linea_id' => $linea_nueva->id, 'ambiente_id' => $ambiente_nuevo->id]
                    );
                }
            }

            if ($codigo) {
                $producto = Producto::updateOrCreate(
                    ['code' => $codigo],
                    [
                        'lampara' => $lampara ?? null,
                        'name' => $nombre,
                        'medidas' => $medidas ?? null,
                        'origen' => $origen ?? null,
                        'espacio_id' => $espacio_nuevo ? $espacio_nuevo->id : null,
                        'uso_id' => $uso_nuevo ? $uso_nuevo->id : null,
                        'linea_id' => $linea_nueva ? $linea_nueva->id : null
                    ]

                );

                if ($producto) {
                    ImagenProducto::updateOrCreate(
                        ['producto_id' => $producto->id, 'image' => "images/" . $codigo . ".png"],
                        ['producto_id' => $producto->id, 'image' => "images/" . $codigo . ".png"]
                    );
                }
            }

            if ($colores && $producto) {
                $colores = array_map('trim', explode(',', $colores));
                foreach ($colores as $color) {
                    $color_model = Color::where('name', $color)->first();
                    if ($color_model) {
                        ProductoColor::updateOrCreate(
                            ['producto_id' => $producto->id, 'color_id' => $color_model->id],
                            ['producto_id' => $producto->id, 'color_id' => $color_model->id]
                        );
                    } else {
                        Log::warning("Color no encontrado: {$color}");
                    }
                }
            }

            if ($ambientes && $producto) {
                foreach ($ambientes as $ambiente) {
                    $ambiente_producto = Ambiente::where('name_es', $ambiente)->first();
                    if (!$ambiente_producto) {
                        Log::warning("Ambiente no encontrado: {$ambiente}");
                        continue;
                    }
                    ProductoAmbiente::updateOrCreate(
                        ['producto_id' => $producto->id, 'ambiente_id' => $ambiente_producto->id],
                        ['producto_id' => $producto->id, 'ambiente_id' => $ambiente_producto->id]
                    );
                }
            }
        }

        Log::info('=== FIN DEBUG EXCEL ===');
    }
}
