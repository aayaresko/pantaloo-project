<?php

return [

    'dsn' => env('SENTRY_LARAVEL_DSN', env('SENTRY_DSN')),

    // capture release as git sha
    'release' => trim(exec('git --git-dir '.base_path('.git').' log --pretty="%h" -n1 HEAD')),

    'breadcrumbs' => [

        // Capture bindings on SQL queries logged in breadcrumbs
        'sql_bindings' => true,

    ],

    'capture_silenced_errors' => true,
    'error_types' => E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_USER_DEPRECATED,

];
