<?php

namespace App\Providers;

use App\Models\CarBrand;
use App\Models\CarCity;
use App\Observers\CarBrandObserver;
use App\Observers\CarCityObserver;
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
        CarCity::observe(CarCityObserver::class);
        CarBrand::observe(CarBrandObserver::class);
    }
}
