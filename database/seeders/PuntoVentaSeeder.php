<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PuntoVenta;

class PuntoVentaSeeder extends Seeder
{
    public function run()
    {
        $puntos = [
            [
                'nombre' => 'ELECTRO REY',
                'direccion' => 'Montemar López May 3057',
                'provincia' => 'Buenos Aires',
                'localidad' => 'San Justo',
                'latitud' => -34.6764,
                'longitud' => -58.5606,
                'telefono' => '011-4626-4938',
                'email' => 'info@electrorey.com.ar',
                'activo' => true
            ],
            [
                'nombre' => 'LAMARINA S.A.',
                'direccion' => 'Av Juan B. Justo 5394 CABA',
                'provincia' => 'Buenos Aires',
                'localidad' => 'Ciudad Autónoma de Buenos Aires',
                'latitud' => -34.5875,
                'longitud' => -58.4756,
                'telefono' => '011-4626-4938',
                'email' => 'info@electrolamarina.com.ar',
                'activo' => true
            ],
            [
                'nombre' => 'HERRAMETAL S.A.',
                'direccion' => 'Calle 188 Vilas Altas 5658 - Prov. de Buenos Aires',
                'provincia' => 'Buenos Aires',
                'localidad' => 'La Plata',
                'latitud' => -34.9011,
                'longitud' => -57.9545,
                'telefono' => '011-4709-5992',
                'email' => 'sifuentes@gmail.com',
                'activo' => true
            ],
            [
                'nombre' => 'ESTEBAN QUILES MIGUEL',
                'direccion' => 'Riobamba 517 Merlo - Prov. de Buenos Aires',
                'provincia' => 'Buenos Aires',
                'localidad' => 'Merlo',
                'latitud' => -34.6629,
                'longitud' => -58.7281,
                'telefono' => '0220-4858500',
                'email' => 'contacto@elviajovati.com.ar',
                'activo' => true
            ],
            [
                'nombre' => 'HERRAJES ADROSUR 2018 SRL',
                'direccion' => 'Ruta 1e Km 696 - Mar del Plata',
                'provincia' => 'Buenos Aires',
                'localidad' => 'Mar del Plata',
                'latitud' => -37.9922,
                'longitud' => -57.5596,
                'telefono' => '0223-4650396',
                'email' => 'info@herrajesadrosur.com',
                'activo' => true
            ],
            [
                'nombre' => 'FERRETERÍA CENTRAL',
                'direccion' => 'Av. San Martin 1245',
                'provincia' => 'Córdoba',
                'localidad' => 'Córdoba',
                'latitud' => -31.4201,
                'longitud' => -64.1888,
                'telefono' => '0351-4567890',
                'email' => 'info@ferreteriacentral.com.ar',
                'activo' => true
            ],
            [
                'nombre' => 'ELÉCTRICA NORTE',
                'direccion' => 'Calle 25 de Mayo 567',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
                'latitud' => -32.9442,
                'longitud' => -60.6505,
                'telefono' => '0341-4234567',
                'email' => 'contacto@electricanorte.com.ar',
                'activo' => true
            ],
            [
                'nombre' => 'ILUMINACIÓN SUR',
                'direccion' => 'Av. Rivadavia 890',
                'provincia' => 'Mendoza',
                'localidad' => 'Mendoza',
                'latitud' => -32.8895,
                'longitud' => -68.8458,
                'telefono' => '0261-4123456',
                'email' => 'ventas@iluminacionsur.com.ar',
                'activo' => true
            ],
            [
                'nombre' => 'CASA LÓPEZ',
                'direccion' => 'San Martin 234',
                'provincia' => 'Tucumán',
                'localidad' => 'San Miguel de Tucumán',
                'latitud' => -26.8083,
                'longitud' => -65.2176,
                'telefono' => '0381-4567890',
                'email' => 'info@casalopez.com.ar',
                'activo' => true
            ],
            [
                'nombre' => 'DISTRIBUIDORA PATAGÓNICA',
                'direccion' => 'Av. Argentina 1567',
                'provincia' => 'Río Negro',
                'localidad' => 'Bariloche',
                'latitud' => -41.1335,
                'longitud' => -71.3103,
                'telefono' => '02944-567890',
                'email' => 'bariloche@distpatagonica.com.ar',
                'activo' => true
            ]
        ];

        foreach ($puntos as $punto) {
            PuntoVenta::create($punto);
        }
    }
}
