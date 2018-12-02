<?php

return [

    'types' => [
        //list
    ],

    'categories' => [

    ],

    'statusSession' => [
        'login' => 0,
        'logout' => 1
    ],

    'providers' =>[
        1 => [
            'code' => 'pantallo',
            'lib' => App\Modules\Games\PantalloGamesSystem::class

        ]
    ],

    'listGames' => [
        'pagination' => [
            'desktop' => 30,
            'mobile' => 6
        ]
    ]
];
