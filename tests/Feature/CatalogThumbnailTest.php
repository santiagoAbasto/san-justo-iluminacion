<?php

use App\Models\Contacto;
use App\Models\Linea;
use App\Models\Logos;
use App\Models\Producto;
use App\Services\CatalogThumbnail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

test('catalog thumbnails preserve aspect ratio, transparency and the original image', function () {
    $disk = Storage::disk('public');
    $disk->makeDirectory('images');
    $source = $disk->path('images/product.png');
    $image = imagecreatetruecolor(1200, 600);
    imagealphablending($image, false);
    imagesavealpha($image, true);
    imagefill($image, 0, 0, imagecolorallocatealpha($image, 0, 0, 0, 127));
    imagefilledrectangle($image, 200, 100, 1000, 500, imagecolorallocate($image, 20, 80, 140));
    imagepng($image, $source);
    $originalHash = hash_file('sha256', $source);

    $service = app(CatalogThumbnail::class);
    expect($service->url('images/product.png'))->toBe(asset('storage/images/product.png'));
    $thumbnail = $service->generate('images/product.png');
    $size = getimagesize($disk->path($thumbnail));
    $webp = imagecreatefromwebp($disk->path($thumbnail));

    expect([$size[0], $size[1], $size['mime']])->toBe([640, 320, 'image/webp']);
    expect(imagecolorsforindex($webp, imagecolorat($webp, 0, 0))['alpha'])->toBe(127);
    expect(hash_file('sha256', $source))->toBe($originalHash);
    expect($service->url('images/product.png'))->toBe(asset('storage/'.$thumbnail));
    expect($service->generate('images/product.png'))->toBe($thumbnail);

    touch($source, time() + 2);
    clearstatcache(true, $source);
    expect($service->thumbnailPath('images/product.png'))->not->toBe($thumbnail);
    expect($service->url('images/product.png'))->toBe(asset('storage/images/product.png'));
});

test('thumbnail generation reports missing originals and can be safely repeated', function () {
    $disk = Storage::disk('public');
    $disk->makeDirectory('images');
    imagepng(imagecreatetruecolor(800, 800), $disk->path('images/product.png'));
    $product = Producto::create(['code' => '7611', 'name' => 'Colgante blanco']);
    $product->imagenes()->create(['image' => 'images/product.png', 'order' => '1']);
    $product->imagenes()->create(['image' => 'images/missing.png', 'order' => '2']);

    $this->artisan('productos:miniaturas')
        ->expectsOutput('Generadas: 1. Existentes: 0. Originales ausentes: 1. Errores: 0.')
        ->assertSuccessful();
    $this->artisan('productos:miniaturas')
        ->expectsOutput('Generadas: 0. Existentes: 1. Originales ausentes: 1. Errores: 0.')
        ->assertSuccessful();
    expect(app(CatalogThumbnail::class)->url('images/missing.png'))
        ->toBe(asset('storage/images/missing.png'));
});

test('product listing serves thumbnails while product detail keeps its original', function () {
    Logos::create(['logo_principal' => 'logos/principal.png', 'logo_secundario' => 'logos/secundario.png']);
    Contacto::create();
    $disk = Storage::disk('public');
    $disk->makeDirectory('images');
    imagepng(imagecreatetruecolor(800, 800), $disk->path('images/product.png'));
    $linea = Linea::create(['order' => '1', 'name_es' => 'Eco Gonnet']);

    for ($index = 1; $index <= 16; $index++) {
        $product = Producto::create(['code' => 'P'.$index, 'name' => 'Producto '.$index, 'linea_id' => $linea->id, 'order' => str_pad($index, 2, '0', STR_PAD_LEFT)]);
        $product->imagenes()->create(['image' => 'images/product.png', 'order' => '1']);
    }
    $thumbnail = app(CatalogThumbnail::class)->generate('images/product.png');

    $response = $this->get(route('productos'))->assertOk();
    $document = new DOMDocument;
    @$document->loadHTML($response->getContent());
    $images = (new DOMXPath($document))->query('//img[contains(@src, "/catalogo/miniaturas/")]');
    expect($images->length)->toBe(16);
    foreach ($images as $index => $image) {
        expect($image->getAttribute('loading'))->toBe($index < 4 ? 'eager' : 'lazy');
    }
    $response->assertSee(asset('storage/'.$thumbnail), false);
    $this->get(route('producto.show.exact', ['id' => $product->code, 'productoId' => $product->id]))
        ->assertOk()
        ->assertSee(asset('storage/images/product.png'), false);
});
