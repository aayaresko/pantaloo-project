<?php

return [

    'defaultTypes' => [
        [
            'id' => 10001,
            'code' => 'our_slots',
            'name' => 'slots',
        ],
        [
            'id' => 10002,
            'code' => 'our_blackjack',
            'name' => 'blackjack',

        ],
        [
            'id' => 10003,
            'code' => 'our_roulette',
            'name' => 'roulette',
        ],
        [
            'id' => 10004,
            'code' => 'our_baccarat',
            'name' => 'baccarat',
        ],
        [
            'id' => 10005,
            'code' => 'our_bet_on_numbers',
            'name' => 'bet on numbers',
        ],
        [
            'id' => 10006,
            'code' => 'our_keno',
            'name' => 'keno',
        ],
        [
            'id' => 10007,
            'code' => 'our_poker',
            'name' => 'poker',
        ],
        [
            'id' => 10008,
            'code' => 'our_dice',
            'name' => 'dice',
        ],
        [
            'id' => 10009,
            'code' => 'our_live_casino',
            'name' => 'live casino',
        ],
        [
            'id' => 10010,
            'code' => 'our_live_games',
            'name' => 'live games',
        ],
        [
            'id' => 10011,
            'code' => 'our_others',
            'name' => 'others',
        ],
        [
            'id' => 10012,
            'code' => 'our_table_games',
            'name' => 'table games',
        ],
        [
            'id' => 10013,
            'code' => 'our_video_poker',
            'name' => 'video poker',
        ],
        [
            'id' => 10014,
            'code' => 'our_virtual_games',
            'name' => 'virtual games',
        ],
        [
            'id' => 10015,
            'code' => 'our_virtual_sports',
            'name' => 'virtual sports',
        ],
        [
            'id' => 10016,
            'code' => 'our_scratch_cards',
            'name' => 'scratch cards',
        ],
        [
            'id' => 10017,
            'code' => 'our_bingo',
            'name' => 'bingo',
        ],
    ],

    'defaultTitle' => 'games',

    'keepLanguage' => 60 * 24 * 30,//min
    'resendMailTime' => 10,
    'linkActiveConfirm' => 60 * 60 * 24,
    'eventStatistic' => [
        'enter' => 1,
        'register' => 2
    ],

    'partnerCommission' => 45,

    'freeRounds' => [
        'available' => 50,
        'timeFreeRound' => 1209600
    ],

    'restrictionMark' => [
        'disable' => 0,
        'enable' => 1
    ],

    'getCountries' => 'https://restcountries.eu/rest/v2/all',

    'defaultmBtcCpu' => 50,

    'minConfirmBtc' => 2,

    'statusPayment' => [
        '-2' => 'failed',
        '-1' => 'frozen',
        '0' => 'pending',
        '1' => 'approve',
        '2' => 'done',
        '3' => 'queue',
    ]

];
