<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
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

    public function boot(): void
    {
        date_default_timezone_set('Asia/Makassar');
        Carbon::setLocale('id');

        Blade::directive('formatdate', function ($expression) {
            return "<?php echo \Carbon\Carbon::parse($expression)->setTimezone('Asia/Makassar')->locale('id')->translatedFormat('l, d F Y'); ?>";
        });
    }
}
