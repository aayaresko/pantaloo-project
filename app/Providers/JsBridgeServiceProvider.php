<?php

namespace App\Providers;

use App\Providers\JsBridge\JsBridge;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;


class JsBridgeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton(JsBridge::class, function () {
            return new JsBridge();
        });
    }
}

