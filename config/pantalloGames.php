<?php

return [

    'forRequest' => [
        'api_login' => env('PANTALLO_LOGIN'),
        'api_password' => env('PANTALLO_PASSWORD'),
        'show_systems' => env('PANTALLO_SYSTEM_SHOW'),
        'currency' => env('PANTALLO_CURRENCY'),
        'url' => env('PANTALLO_URL'),
        'ssl' => '',
        'connectTimeout' => 240,
    ],

    'additional' => [
        'salt' => env('PANTALLO_SALT'),
        'action' => [
            'debit' => 1,
            'credit' => 2,
            'rollback' => 3,
            'freeRound' => 4,
        ],
        'actionFreeRounds' => [
            'debit' => 9,
            'credit' => 10,
        ],
        'operation' => [
            'debit' => 'bcsub',
            'credit' => 'bcadd',
        ],
    ],

    'prefixName' => env('PANTALLO_PREFIX_NAME', 'CBit'),

    'usePrefixAfter' => '2019-04-25 00:00:00',
];
