<?php

namespace App\Console\Commands;

use App\Models\AgentsKoef;
use App\Models\AgentSum;
use App\Models\UserSum;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransactionSum extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:sum';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sum transaction';

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
        ini_set('memory_limit','4096M');
        $agents = User::whereIn('role', [1, 3])->get();
        foreach ($agents as $agent) {
            if ($agent->email == 'affiliate@casinobit.io' and $agent->role == 3) {
                $agent->role = 4;
                $agent->save();
            }
            $newAgent = AgentsKoef::where('user_id', $agent->id)->first();
            if (!$newAgent) {
                $newAgent = new AgentsKoef();
                $newAgent->user_id = $agent->id;
                $newAgent->koef = $agent->commission ?: 0;
                $newAgent->created_at = Carbon::now()->subDays(100);
                $newAgent->save();
            }
        }
        //deep start init
        $trim = 100;
        //just for optimisation speed
        $lastId = 0;

        $casinoFit = config('partner.casino_fit');
        for ($i = 0; $i <= $trim; $i++) {
            $now = Carbon::now()->subDays($trim)->addDays($i)->startOfDay();
            //show info to console
            $this->info($now->toDateString() . " Last id: $lastId");

            $transactionsUser = Transaction::where('created_at', '<', $now->toDateTimeString())
                ->where('id', '>', $lastId)
                ->where('created_at', '>=', $now->subDay()->toDateTimeString())
                ->get()
                ->groupBy('user_id');
            $now->addDay();
            foreach ($transactionsUser as $userId => $transactions) {
                $userSum = new UserSum();
                $userSum->user_id = $userId;
                $userSum->deposits = 0;
                $userSum->created_at = $now;
                $userSum->bets = 0;
                $userSum->wins = 0;
                $userSum->bonus = 0;
                $userSum->bet_count = 0;
                $depositBonusSum = 0;
                foreach ($transactions as $transaction) {
                    if ($transaction->type == 3) {
                        $userSum->deposits += $transaction->sum;
                    } elseif ($transaction->type == 1 or $transaction->type == 2) {
                        if ($transaction->type == 1) {
                            $userSum->bets += $transaction->sum;
                            $userSum->bet_count += 1;
                        } else {
                            $userSum->wins += $transaction->sum;
                        }

                        $userSum->bonus += $transaction->bonus_sum;
                    }
                    if ($transaction->type == 5) {
                        $depositBonusSum += $transaction->bonus_sum;
                    }
                }
                //Formula:
                //
                //Net Gaming Revenue = *Bets - Wins - Bonuses - Admin Fee (18%)
                // *we have bets as negative
                $userSum->sum = ($userSum->bets + $userSum->wins + $depositBonusSum) * (100 - $casinoFit) / 100;
                $userSum->save();
                $lastId = $transaction->id;

                if ($agent_id = @User::find($userId)->agent_id and $agent = $agents->where('id', $agent_id)->first()) {
                    //if someone want to change user's agent we need old information
                    $userSum->parent_id = $agent->id;
                    $userSum->percent = $agent->commission;
                    $userSum->save();


                    $agentSum = AgentSum::where('user_id', $agent_id)->where('created_at', $now->toDateTimeString())->first();
                    if (!$agentSum) {
                        $agentSum = new AgentSum();
                        $agentSum->user_id = $agent_id;
                        $agentSum->created_at = $now;
                    }
                    $agentSum->total_sum += $userSum->sum;
                    $agentSum->agent_percent = $agent->koefs->koef;
                    if ($agent->agent_id) {
                        $agentSum->parent_percent = $agent->parentKoef->koef;
                        $agentSum->parent_profit += $userSum->sum * ($agentSum->parent_percent - $agentSum->agent_percent) / 100;
                        $parent = $agents->where('id', $agent->agent_id)->first();
                        //if parent has parent -> add parent profit recursively
                        if ($parent->agent_id) {
                            $this->addParentProfit($agent->agent_id, $userSum->sum, $now, $agents);
                        }
                    }
                    $agentSum->save();
                }
            }
        }
    }

    //recursively add parent profit
    protected function addParentProfit($agent_id, $sum, $now, $agents)
    {
        //check parent is agent
        $agent = @$agents->where('id', $agent_id)->first();
        if (!$agent) {
            return;
        }

        $agentSum = AgentSum::where('user_id', $agent_id)->where('created_at', $now->toDateTimeString())->first();
        if (!$agentSum) {
            $agentSum = new AgentSum();
            $agentSum->user_id = $agent_id;
            $agentSum->created_at = $now;
            $agentSum->total_sum = 0;
            $agentSum->agent_percent = $agent->koefs->koef;
            $agentSum->parent_percent = $agent->parentKoef->koef;
        }
        $agentSum->parent_profit += $sum * ($agentSum->parent_percent - $agentSum->agent_percent) / 100;
        $agentSum->save();

        $parent = $agents->where('id', $agent->agent_id)->first();
        if ($parent->agent_id) {
            $this->addParentProfit($agent->agent_id, $sum, $now, $agents);
        }
    }
}
