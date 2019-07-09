<?php

namespace App\Providers;

use App\Http\View\Composers\BreadcrumbComposer;
use Illuminate\Support\ServiceProvider;

class ComposerSericeProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['integrated_games',
            'chat',
            'bonuses',
            'contact_us',
            'privacy-policy',], BreadcrumbComposer::class);
    }
}
