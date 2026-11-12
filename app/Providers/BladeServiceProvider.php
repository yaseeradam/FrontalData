<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Custom directive for Livewire styles
        Blade::directive('livewireStyles', function () {
            return '<link href="{{ asset(\'css/livewire.css\') }}" rel="stylesheet">';
        });

        // You can add more custom directives here
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
