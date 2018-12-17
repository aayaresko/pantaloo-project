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
    ],

    'dummyPicture' => '/media/images/preloader/image-not-available.png',

    'listSettings' => [
        1 => ['id', 'asc'],
        2 => ['id', 'desc'],
        3 => ['rating', 'asc'],
        4 => ['rating', 'desc'],
    ],

    'typeTransaction' => [
            -1 => 'Bet + Win',
            0 => 'Transaction type / All',
            1 => 'Bet',
            2 => 'Win',
            3 => 'Deposit',
            4 => 'Withdraw',
            5 => 'Bonus activation',
            6 => 'Bonus cancellation',
            7 => 'Bonus to real',
            8 => 'Free spins add',
            9 => 'Free Bet',
            10 => 'Free Win'
    ]
];
