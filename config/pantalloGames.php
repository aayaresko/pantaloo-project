<?php

return [

    'forRequest' => [
        'api_login' => 'casinobit_mc_s',
        'api_password' => 'SPHhcXLHSZyg28OlpY',
        'show_systems' => 0,
        'currency' => 'USD',
        'url' => 'https://stage.game-program.com/api/seamless/provider',
        'ssl' => '',
    ],

    'additional' => [
        'salt' => 'REnd48fg3',
        'action' => [
            'debit' => 1,
            'credit' => 2,
            'rollback' => 3
        ],
        'operation' => [
            'debit' => 'bcadd',
            'credit' => 'bcsub',
        ]
    ]
];
