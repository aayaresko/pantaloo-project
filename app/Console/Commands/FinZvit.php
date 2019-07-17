<?php

namespace App\Console\Commands;

use App\Transaction;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class FinZvit extends Command
{
    private static $res = '';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'finzvit:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send fin zvit';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userOffice = [
            2532,
            2528,
            2520,
            2518,
            2516,
            2515,
            2514,
            2512,
            2501,
            2500,
            2499,
            2494,
            2493,
            2491,
            2489,
            2488,
            2486,
            2484,
            2483,
            2480,
            2479,
            2477,
            2476,
            2474,
            2473,
            2472,
            2471,
            2470,
            2468,
            2467,
            2466,
            2465,
            2456,
            2451,
            2446,
            2445,
            2444,
            2443,
            2442,
            2441,
            2438,
            2399,
            1162,
            1055,
            1041,
            1031,
            1024,
            1021,
            1011,
            1007,
            998,
            975,
            971,
            456,
            442,
            432,
            427,
            402,
            379,
            376,
            372,
            370,
            368,
            366,
            365,
            345,
            332,
            328,
            323,
            318,
            308,
            307,
            306,
            44,
            2411,
            348,
            349,
            350,
            351,
            352,
            353,
            354,
            359,
            361,
            362,
            363,
            364,
            367,
            369,
            371,
            373,
            374,
            375,
            377,
            378,
            380,
            381,
            382,
            383,
            384,
            385,
            386,
            387,
            330,
            319,
            2426,
            2428,
            389,
            390,
            391,
            394,
            396,
            398,
            399,
            405,
            338,
            340,
            341,
            342,
            343,
            344,
            346,
            256,
            257,
            258,
            259,
            260,
            261,
            264,
            275,
            276,
            277,
            278,
            281,
            282,
            283,
            284,
            285,
            286,
            287,
            288,
            289,
            290,
            292,
            293,
            294,
            295,
            296,
            299,
            162,
            167,
            169,
            214,
            215,
            219,
            220,
            222,
            224,
            240,
            241,
            242,
            243,
            244,
            247,
            248,
            249,
            250,
            251,
            252,
            253,
            254,
            38,
            39,
            40,
            41,
            42,
            64,
            85, 136, 146, 956,
        ];

        $startDate = '2019-06-01 00:00:00';
        $endDate = '2019-07-01 00:00:00';

        $startDate = Carbon::now()->subMonth()->startOfMonth()->__toString();
        $endDate = Carbon::now()->subMonth()->endOfMonth()->__toString();

        self::dump('FROM ' . $startDate . ' TO ' . $endDate);

        self::dump('TOTAL');
        $select1 = Transaction::select([DB::raw('sum(sum) as sum_sum'), DB::raw('sum(bonus_sum) as sum_bonus_sum')])
            ->where('created_at', '>', $startDate)
            ->where('created_at', '<', $endDate);

        self::dump('withdraw 4');
        $a1 = clone $select1;
        self::dump($a1->where('type', 4)->first()->toArray());

        self::dump('deposit 3');
        $a2 = clone $select1;
        self::dump($a2->where('type', 3)->first()->toArray());

        self::dump('debit 1');
        $a3 = clone $select1;
        self::dump($a3->where('type', 1)->first()->toArray());

        self::dump('credit 2');
        $a4 = clone $select1;
        self::dump($a4->where('type', 2)->first()->toArray());

        self::dump('free 9');
        $a4 = clone $select1;
        self::dump($a4->where('type', 9)->first()->toArray());

        self::dump('free 10');
        $a5 = clone $select1;
        self::dump($a5->where('type', 10)->first()->toArray());

        self::dump('');
        self::dump('WITHOUT USERS');

        $select2 = Transaction::select([DB::raw('sum(sum) as sum_sum'), DB::raw('sum(bonus_sum) as sum_bonus_sum')])
            ->whereNotIn('user_id', $userOffice)
            ->where('created_at', '>', $startDate)
            ->where('created_at', '<', $endDate);

        self::dump('withdraw 4');
        $a12 = clone $select2;
        self::dump($a12->where('type', 4)->first()->toArray());

        self::dump('deposit 3');
        $a22 = clone $select2;
        self::dump($a22->where('type', 3)->first()->toArray());

        self::dump('debit 1');
        $a32 = clone $select2;
        self::dump($a32->where('type', 1)->first()->toArray());

        self::dump('credit 2');
        $a42 = clone $select2;
        self::dump($a42->where('type', 2)->first()->toArray());

        self::dump('free 9');
        $a42 = clone $select2;
        self::dump($a42->where('type', 9)->first()->toArray());

        self::dump('free 10');
        $a52 = clone $select2;
        self::dump($a52->where('type', 10)->first()->toArray());

        echo self::$res;

        Mail::raw(self::$res, function ($message) {
            $message->to('michkire@gmail.com')->cc('michkire@gmail.com');
        });
    }

    private static function dump($var)
    {
        if (is_string($var)) {
            self::$res .= $var . PHP_EOL;
        }

        if (is_array($var)) {
            foreach ($var as $k => $v) {
                self::$res .= "  " . $k . ': ' . $v . PHP_EOL;
            }
        }
    }
}
