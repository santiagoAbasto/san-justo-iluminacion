<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
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

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
        Blade::directive('lang', function ($expression) {
            return "<?php echo App\Helpers\LocaleHelper::getField($expression); ?>";
        });
    }
}
