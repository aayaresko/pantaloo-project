<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Providers\EmailChecker\EmailChecker;
use Illuminate\Contracts\Support\DeferrableProvider;

class EmailCheckerServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(EmailChecker::class, function ($app) {
            return new EmailChecker();
        });
    }

//    public function provides(){
//        return [EmailChecker::class];
//    }
}
