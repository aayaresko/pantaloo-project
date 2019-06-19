<?php

return [
    'forceHttps' => env('FORCE_HTTPS', true),

    'ipQualityScore' => env('IP_QUALITY_SCORE'),

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

    'slotTypeId' => 10001,

    'defaultTitle' => 'games',

    'keepLanguage' => 60 * 24 * 30, //min
    'resendMailTime' => 10,
    'linkActiveConfirm' => 60 * 60 * 24,
    'eventStatistic' => [
        'enter' => 1,
        'register' => 2,
    ],

    'partnerCommission' => 45,

    'freeRounds' => [
        'available' => 50,
        'timeFreeRound' => 1209600,
    ],

    'restrictionMark' => [
        'disable' => 0,
        'enable' => 1,
    ],

    'getCountries' => 'https://restcountries.eu/rest/v2/all',

    'defaultmBtcCpu' => 50,

    'minConfirmBtc' => 2,

    'normalConfirmBtc' => 6,

    'statusPayment' => [
        '-2' => 'failed',
        '-1' => 'frozen',
        '0' => 'pending',
        '1' => 'approve',
        '2' => 'done',
        '3' => 'queue',
    ],

    'cpaCurrencyCode' => 'mBTC',

    'allowIps' => [
        '213.169.83.245',
    ],

    'depositNotifications' => [
        1 => 'usual bonus notifications',
        2 => 'free spins bonus notifications',
    ],

    'emailsShowAllGames' => [
        'tuzomi@mail-list.top',
    ],

    'disableRegistration' => ['US', 'UA', 'IL', 'XX'],

    'banedBonusesCountries' => [
        'AF', 'AL', 'DZ', 'AO', 'AT', 'CS', 'BH', 'BD', 'BY', 'BJ', 'BO', 'BA', 'BW',
        'BF', 'BG', 'BI', 'CM', 'CV', 'CF', 'TD', 'KM', 'CG', 'CD', 'HR', 'CY', 'CZ',
        'CI', 'DK', 'DJ', 'EG', 'GQ', 'ER', 'ET', 'FI', 'FR', 'GA', 'GM', 'GE', 'GH',
        'GR', 'GN', 'GW', 'GY', 'HT', 'HN', 'HU', 'IN', 'ID', 'IR', 'IQ', 'JO', 'KZ',
        'KE', 'KW', 'LV', 'LB', 'LS', 'LR', 'LT', 'MK', 'MG', 'MW', 'MY', 'ML', 'MR',
        'MU', 'MD', 'MN', 'MA', 'MZ', 'NA', 'NI', 'NP', 'NE', 'NG', 'KP', 'OM', 'PK',
        'PH', 'PL', 'PT', 'RO', 'RU', 'RW', 'ST', 'SN', 'SC', 'SL', 'SK', 'SI', 'SO',
        'SD', 'CH', 'SY', 'TH', 'TG', 'TN', 'UG', 'UA', 'AE', 'TZ', 'VN', 'YE', 'ZM',
        'ZW', 'ME', 'RS', 'XX'],
    
    'ipQualityScoreUrl' => 'https://www.ipqualityscore.com/api/json/ip',

    'ipQualityScoreKey' => env('IP_QUALITY_SCORE'),

    'rawLogKey' => [
        //to do others form pantallo scripts
        'freeSpins1' => 20, //plus id bonus
        'depositBonus2' => 30,
        'depositBonus3' => 40,
        'depositBonus4' => 50,
    ],

    'optimization' => [
        'clearRawLog' => '30 day',
    ],

    'officeIps' => [
        '136.0.0.139'
    ],

    'users' => [
        'roles' => [
            [
                'key' => -3,
                'name' => 'Admin test',
                'noEdit' => 0
            ],
            [
                'key' => -2,
                'name' => 'Affiliate test',
                'noEdit' => 0
            ],
            [
                'key' => -1,
                'name' => 'User test',
                'noEdit' => 0
            ],
            [
                'key' => 0,
                'name' => 'User',
                'noEdit' => 0
            ],
            [
                'key' => 1,
                'name' => 'Affiliate',
                'noEdit' => 0
            ],
            [
                'key' => 2,
                'name' => 'Admin',
                'noEdit' => 1
            ]
        ],
    ]
];
