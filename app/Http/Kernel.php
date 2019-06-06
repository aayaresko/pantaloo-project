<?php

namespace App\Http;

use App\Http\Middleware\IpCheck;
use App\Http\Middleware\AdminCheck;
use App\Http\Middleware\AgentCheck;
use App\Http\Middleware\LanguageGet;
use App\Http\Middleware\LanguageSet;
use App\Http\Middleware\UserToAgent;
use App\Http\Middleware\IpCountryBlock;
use App\Http\Middleware\LanguageSwitch;
use App\Http\Middleware\SessionReflash;
use App\Http\Middleware\SetCountryCode;
use App\Http\Middleware\EmailConfirmation;
use App\Http\Middleware\GeneralMiddleware;
use App\Http\Middleware\IpDomainCountryBlock;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \App\Http\Middleware\UsersOnline::class,
            UserToAgent::class,
            //LanguageGet::class
            //LanguageSet::class,
            //EmailConfirmation::class,
            SetCountryCode::class,
            GeneralMiddleware::class,
        ],
        'landing' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
        ],
        'ajax' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        ],
        'games' => [],
        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'agent' => AgentCheck::class,
        'admin' => AdminCheck::class,
        'email.confirm' => EmailConfirmation::class,
        'ip.check' => IpCheck::class,
        'language.switch' =>  LanguageSwitch::class,
        'session.reflash' =>  SessionReflash::class,
        'ip.country.block' => IpCountryBlock::class,
        'ip.domain.country.block' => IpDomainCountryBlock::class,
    ];
}
