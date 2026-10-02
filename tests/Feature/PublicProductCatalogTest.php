<?php

use App\Models\Ambiente;
use App\Models\Contacto;
use App\Models\Espacio;
use App\Models\Linea;
use App\Models\Logos;
use App\Models\Producto;
use App\Models\Uso;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Logos::create([
        'logo_principal' => 'logos/principal.png',
        'logo_secundario' => 'logos/secundario.png',
    ]);
    Contacto::create();
});

test('product filters only offer combinations that still have results', function () {
    $exterior = Espacio::create(['order' => '1', 'name_es' => 'Exterior']);
    $interior = Espacio::create(['order' => '2', 'name_es' => 'Interior']);

    $faroles = Uso::create(['order' => '1', 'name_es' => 'Faroles', 'espacio_id' => $exterior->id]);
    $colgantes = Uso::create(['order' => '2', 'name_es' => 'Colgantes', 'espacio_id' => $interior->id]);

    $ecoGonnet = Linea::create(['order' => '1', 'name_es' => 'Eco Gonnet']);
    $oficina = Linea::create(['order' => '2', 'name_es' => 'Oficina']);

    $jardin = Ambiente::create(['order' => '1', 'name_es' => 'Jardín']);
    $living = Ambiente::create(['order' => '2', 'name_es' => 'Living']);

    $productoExterior = Producto::create([
        'order' => '1',
        'name' => 'Farol Eco Gonnet',
        'code' => '7611',
        'espacio_id' => $exterior->id,
        'uso_id' => $faroles->id,
        'linea_id' => $ecoGonnet->id,
    ]);
    $productoExterior->ambientes()->attach($jardin);

    $productoInterior = Producto::create([
        'order' => '2',
        'name' => 'Colgante de oficina',
        'code' => '9000',
        'espacio_id' => $interior->id,
        'uso_id' => $colgantes->id,
        'linea_id' => $oficina->id,
    ]);
    $productoInterior->ambientes()->attach($living);

    $response = $this->get(route('productos', [
        'espacio' => $exterior->id,
        'uso' => $faroles->id,
    ]));

    $response->assertOk();
    $response->assertViewHas('productos', fn ($productos) => $productos->getCollection()->modelKeys() === [$productoExterior->id]);
    $response->assertViewHas('lineas', fn ($lineas) => $lineas->modelKeys() === [$ecoGonnet->id]);
    $response->assertViewHas('ambientes', fn ($ambientes) => $ambientes->modelKeys() === [$jardin->id]);
    $response->assertViewHas('usos', fn ($usos) => $usos->modelKeys() === [$faroles->id]);
    $response->assertViewHas('espaciosDisponibles', fn ($espacios) => $espacios->modelKeys() === [$exterior->id]);

    // Validar el select renderizado: el composer global también comparte espacios.
    $document = new DOMDocument();
    @$document->loadHTML('<?xml encoding="UTF-8">'.$response->getContent());
    $options = (new DOMXPath($document))->query('//select[@id="espacio"]/option');
    $spaceValues = [];
    foreach ($options as $option) {
        $spaceValues[] = $option->getAttribute('value');
    }
    expect($spaceValues)->toBe(['', (string) $exterior->id]);
    $response->assertSee(route('producto.show.exact', [
        'id' => $productoExterior->code,
        'productoId' => $productoExterior->id,
    ]), false);
    $response->assertDontSee('Oficina');
    $response->assertDontSee('Living');
});

test('a product card opens the exact product even when legacy codes are duplicated', function () {
    $linea = Linea::create(['order' => '1', 'name_es' => 'Eco Gonnet']);

    Producto::create([
        'order' => '1',
        'name' => 'Versión anterior',
        'code' => '7611',
        'linea_id' => $linea->id,
    ]);

    $productoCorrecto = Producto::create([
        'order' => '2',
        'name' => 'Versión blanca correcta',
        'code' => '7611',
        'linea_id' => $linea->id,
    ]);

    $response = $this->get(route('producto.show.exact', [
        'id' => '7611',
        'productoId' => $productoCorrecto->id,
    ]));

    $response->assertOk();
    $response->assertViewHas('producto', fn ($producto) => $producto->is($productoCorrecto));
    $response->assertSee('Versión blanca correcta');
});
