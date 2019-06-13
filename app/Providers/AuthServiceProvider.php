<?php

namespace App\Providers;

use App\Policies\AdminPanelPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //

        Gate::define('accessUserAdmin', AdminPanelPolicy::class.'@accessUserAdmin');
        Gate::define('accessUserTranslator', AdminPanelPolicy::class.'@accessUserTranslator');
        Gate::define('accessUserAdminPublic', AdminPanelPolicy::class.'@accessUserAdminPublic');
        Gate::define('accessUserAffiliate', AdminPanelPolicy::class.'@accessUserAffiliate');
        Gate::define('accessAdminAffiliatePublic', AdminPanelPolicy::class.'@accessAdminAffiliatePublic');
        Gate::define('accessAdminTranslatorPublic', AdminPanelPolicy::class.'@accessAdminTranslatorPublic');
    }
}
