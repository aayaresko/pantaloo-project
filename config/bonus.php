<?php

return [

    'freeSpins' => [
        'afterRegistrationActive' => 432000,
        'workTime' => 86400,
    ],

    'operation' => [
        'active' => 1,
        'realActivation' => 2,
        'close' => 3,
        'cancel' => 4,
        'wagerUpdate' => 5,
        'setDeposit' => 6,
        'setGame' => 7,
    ],

    'classes' => [
        1 => App\Bonuses\FreeSpins::class,
        2 => App\Bonuses\Bonus_200::class,
        3 => App\Bonuses\Bonus_150::class,
        4 => App\Bonuses\Bonus_100::class,
    ],

    'checkFrequency' => 10,

];
