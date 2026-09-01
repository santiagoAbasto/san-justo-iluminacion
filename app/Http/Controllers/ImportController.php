<?php

namespace App\Http\Controllers;

use App\Console\Commands\ImportarExcelProductos;
use App\Jobs\ActualizarPreciosJob;
use App\Jobs\ImportarClientesJob;
use App\Jobs\ImportarOfertasJob;
use App\Jobs\ImportarProductosDesdeExcelJob;
use App\Jobs\ImportarPuntosJob;
use App\Jobs\ImportarVendedoresJob;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\PuntoVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Producto;

class ImportController extends Controller
{
    public function importar(Request $request)
    {

        // Guardar archivo en almacenamiento temporal
        $archivoPath = str_replace('/storage/', '', parse_url($request->path, PHP_URL_PATH));

        $lista_id = $request->lista_id;


        // Encolar el Job
        ActualizarPreciosJob::dispatch($archivoPath, $lista_id);
    }

    public function importarProductos(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls'
        ]);
        // Guardar archivo en almacenamiento temporal
        $archivoPath = $request->file('archivo')->store('importaciones');

        // Encolar el Job
        ImportarProductosDesdeExcelJob::dispatch($archivoPath);
    }
    
    public function sincronizarprodu()
{
    $folder = 'certificados'; // Carpeta donde subiste los PDFs dentro de storage/app/public
    $productos = Producto::all();
    $asignados = 0;

    foreach ($productos as $producto) {
        $file = $folder . '/' . $producto->code . '.pdf';

        // Solo toca si existe un archivo con el mismo cÃ³digo
        if (Storage::disk('public')->exists($file)) {
            // Si ya tiene ese certificado no hace nada
            if ($producto->certificado !== $file) {
                $producto->certificado = $file;
                $producto->save();
                $asignados++;
            }
        }
    }

    return "SincronizaciÃ³n finalizada. Se asignaron/actualizaron {$asignados} certificados.";
}

private function normalizarCoordenada($valor)
{
    if (!$valor) return null;

    // Quitar espacios
    $valor = trim($valor);

    // Reemplazar comas por puntos
    $valor = str_replace(',', '.', $valor);

    // Si hay m¨¢s de un punto decimal, dejamos el primero como separador y juntamos el resto
    if (substr_count($valor, '.') > 1) {
        $partes = explode('.', $valor);
        $valor = $partes[0] . '.' . implode('', array_slice($partes, 1));
    }

    return $valor;
}




public function importarClientes(Request $request)
{
    $request->validate([
        'archivo' => 'required|mimes:xlsx,xls'
    ]);

    $archivoPath = $request->file('archivo')->store('importaciones');
    $filePath = Storage::path($archivoPath);

    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray(null, true, true, true);

    Log::info("=== INICIO IMPORT PUNTOS DE VENTA ===");
    Log::info("Total de filas: " . count($rows));

    foreach ($rows as $index => $row) {
        if ($index === 1 || strtoupper(trim($row['A'])) === 'NOMBRE') {
            continue;
        }

        $nombre    = trim($row['A']);
        $direccion = trim($row['B']);
        $provincia = trim($row['C']);
        $localidad = trim($row['D']);
        $telefono  = trim($row['E']);
        $email     = trim($row['F']);
        $latitud   = $this->normalizarCoordenada($row['G']);
        $longitud  = $this->normalizarCoordenada($row['H']);

        Log::info("Fila $index => Nombre: $nombre | Provincia: $provincia | Localidad: $localidad | Lat: $latitud | Lng: $longitud");

        PuntoVenta::updateOrCreate(
            ['nombre' => $nombre],
            [
                'direccion'   => $direccion ?: '',
                'provincia'   => $provincia ?: ' ',
                'localidad'   => $localidad ?: '',
                'telefono'    => $telefono ?: null,
                'email'       => $email ?: null,
                'latitud'     => $latitud,
                'longitud'    => $longitud,
                'descripcion' => null,
                'activo'      => true,
            ]
        );
    }

    Log::info("=== FIN IMPORT PUNTOS DE VENTA ===");

    return back()->with('success', 'Puntos de venta importados correctamente');
}


    public function importarVendedores(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls'
        ]);
        // Guardar archivo en almacenamiento temporal
        $archivoPath = $request->file('archivo')->store('importaciones');

        // Encolar el Job
        ImportarVendedoresJob::dispatch($archivoPath);
    }

    public function importarOfertas(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls'
        ]);
        // Guardar archivo en almacenamiento temporal
        $archivoPath = $request->file('archivo')->store('importaciones');

        // Encolar el Job
        ImportarOfertasJob::dispatch($archivoPath);
    }
}
