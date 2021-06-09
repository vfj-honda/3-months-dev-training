<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::if('admin', function () {
            if (auth()->user() && auth()->user()->authority == 1) {
                return true;
            }
            return false;
        });

        Blade::if('user', function () {
            if (auth()->user() && auth()->user()->authority == 0) {
                return true;
            }
            return false;
        });
    }
}
