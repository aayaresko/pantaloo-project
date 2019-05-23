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
            'desktop' => 15,
            'mobile' => 10
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
        [
            'code' => 0,
            'value' => 'Transaction type / All',
            'filter' => 1
        ],
        [
            'code' => -1,
            'value' => 'Bet + Win',
            'filter' => 1
        ],
        [
            'code' => 1,
            'value' => 'Bet',
            'filter' => 1
        ],
        [
            'code' => 2,
            'value' => 'Win',
            'filter' => 1
        ],
        [
            'code' => 3,
            'value' => 'DepositEvent',
            'filter' => 1
        ],
        [
            'code' => 4,
            'value' => 'Withdraw',
            'filter' => 1
        ],
        [
            'code' => 5,
            'value' => 'Bonus activation',
            'filter' => 0
        ],
        [
            'code' => 6,
            'value' => 'Bonus cancellation',
            'filter' => 0
        ],
        [
            'code' => 7,
            'value' => 'Bonus to real',
            'filter' => 0
        ],
        [
            'code' => 8,
            'value' => 'Free spins add',
            'filter' => 0
        ],
        [
            'code' => 9,
            'value' => 'Free Bet',
            'filter' => 0
        ],
        [
            'code' => 10,
            'value' => 'Free Win',
            'filter' => 0
        ],
        [
            'code' => 11,
            'value' => 'System',
            'filter' => 0
        ],
        [
            'code' => 12,
            'value' => 'Trim bonus amount',
            'filter' => 0
        ],
    ]
];
