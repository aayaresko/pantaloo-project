<?php

return [

    'forRequest' => [
        'api_login' => env('PANTALLO_LOGIN'),
        'api_password' => env('PANTALLO_PASSWORD'),
        'show_systems' => env('PANTALLO_SYSTEM_SHOW'),
        'currency' => env('PANTALLO_CURRENCY'),
        'url' => env('PANTALLO_URL'),
        'ssl' => '',
    ],

    'additional' => [
        'salt' => env('PANTALLO_SALT'),
        'action' => [
            'debit' => 1,
            'credit' => 2,
            'rollback' => 3
        ],
        'operation' => [
            'debit' => 'bcsub',
            'credit' => 'bcadd',
        ]
    ]
];
