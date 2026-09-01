<?php

namespace App\Providers;

use App\Models\Contacto;
use App\Models\Espacio;
use App\Models\Logos;
use App\Models\Provincia;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        View::composer('*', function ($view) {
            $view->with([

                'espacios' => Espacio::orderBy('order', 'asc')->with('usos')->get(),
                'contacto' => Contacto::first(),
                'logos' => Logos::first()
            ]);
        });
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
        Blade::directive('lang', function ($expression) {
            return "<?php echo App\Helpers\LocaleHelper::getField($expression); ?>";
        });
    }
}
