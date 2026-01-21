<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
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
        // bootstrapt
        Paginator::useBootstrapFive();
        Carbon::setLocale('id');

        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }
    }
}
