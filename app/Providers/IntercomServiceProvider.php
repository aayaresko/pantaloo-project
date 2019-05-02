<?php

namespace App\Providers;

use App\Providers\Intercom\Intercom;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class IntercomServiceProvider extends ServiceProvider implements DeferrableProvider
{

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Intercom::class, function ($app) {
           return new Intercom();
        });
    }

    public function provides(){
        return [Intercom::class];
    }
}
