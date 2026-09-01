<?php

namespace App\Providers;

use App\Models\Contacto;
use App\Models\Espacio;
use App\Models\Logos;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            static $sharedData;

            $sharedData ??= [
                'contacto' => Contacto::first(),
                'logos' => Logos::first(),
                'espacios' => Espacio::orderBy('order', 'asc')->with('usos')->get(),
            ];

            $view->with($sharedData);
        });
    }

    public function register() {}
}
