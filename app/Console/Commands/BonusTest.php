<?php

namespace App\Console\Commands;

use App\Bonus;
use App\Bonuses\Bonus_100;
use App\Slots\Casino;
use App\Transaction;
use App\User;
use App\UserBonus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class BonusTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bonus:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bonus emulator';

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
        /*
        $bonus = Bonus::find(1);
        $class = $bonus->getClass();
        $bonus_obj = new $class(User::find(1));

        echo $bonus_obj->getPercent();

        exit;
        */
        $transaction = new Transaction();
        $transaction->sum = 20;
        $transaction->bonus_sum = 0;
        $transaction->ext_id = rand();
        $transaction->confirmations = 20;
        $transaction->type = 3;
        $transaction->user()->associate(User::find(1));

        User::find(1)->changeBalance($transaction);

        exit;
        $casino = new Casino(env('CASINO_OPERATOR_ID'), env('CASINO_KEY'));
        $casino->NetEnt(json_decode('{"operatorId":"128300","username":1,"sessionId":"58cb9b436d3181919170902-d4675db0c1","gameId":"182"}', true));

        exit;
        $persons = 0;

        $winners = 0;
        $win_sum = 0;

        for($i = 0; $i < 10000; $i = $i + 1) {
            $bouns_balance = 12.5;
            $play_factor = 45;
            $played_sum = 0;
            $played_sum_need = $bouns_balance * $play_factor;
            $bet_sum = 0.25;

            $bouns_balance = 0;

            for($z = 0; $z < 50; $z = $z + 1)
            {
                $bouns_balance = $bouns_balance + $this->play(0.25);
            }

            while (true) {
                if ($bouns_balance > 0 and $played_sum < $played_sum_need) {
                    $played_sum = $played_sum + $bet_sum;

                    $bouns_balance = $bouns_balance - $bet_sum;
                    $bouns_balance = $bouns_balance + $this->play($bet_sum, 47);
                }
                else break;
            }

            $persons = $persons + 1;

            if($bouns_balance > 0)
            {
                $winners = $winners + 1;
                $win_sum = $win_sum + $bouns_balance;
            }
        }

        echo "Winners: " . $winners . "\n";
        echo "Win sum: " . $win_sum . "\n";
    }

    public function play($sum, $win_procent = 45)
    {
        if(rand(0, 100) <= $win_procent)
        {
            return $sum * 2;
        }
        else return 0;
    }
}
