<?php

use App\Models\BannerPortada;
use App\Models\Contacto;
use App\Models\Logos;
use App\Models\Titulo;

test('home listens for a successful newsletter submission before loading Bitrix', function () {
    Logos::create([
        'logo_principal' => 'logos/principal.png',
        'logo_secundario' => 'logos/secundario.png',
    ]);
    Contacto::create();
    BannerPortada::create(['custom_title_es' => 'Sumate a nuestro newsletter']);

    foreach (['espacios', 'lineas', 'marcas'] as $section) {
        Titulo::create(['seccion' => $section, 'title_es' => $section, 'title_en' => $section]);
    }

    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('id="dailyFormModal"', false);
    $response->assertSee("window.addEventListener('b24:form:send:success'", false);
    $response->assertSee("identification.sec !== 'akv4xt'", false);
    $response->assertSee('sanjusto_popup_form_completed_v1', false);
    $response->assertSee('SameSite=Lax', false);
    $response->assertDontSee("form.subscribe('complete'", false);

    $html = $response->getContent();
    expect(strpos($html, "window.addEventListener('b24:form:send:success'"))
        ->toBeLessThan(strpos($html, 'setTimeout(showModal, 1000)'));
});
