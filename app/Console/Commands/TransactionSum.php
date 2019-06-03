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
                $koefTransaction = Transaction::where('agent_id', $agent->id)->where('agent_commission', '>', 0)->first();
                $newAgent = new AgentsKoef();
                $newAgent->user_id = $agent->id;
                $newAgent->koef = $koefTransaction ? $koefTransaction->agent_commission : 40;
                $newAgent->created_at = Carbon::now()->subDays(100);
                $newAgent->save();
            }
        }
        $trim = 100;
        $lastId = 0;
        $casinoFit = config('partner.casino_fit');
        for ($i = 0; $i < $trim; $i++) {
            $now = Carbon::now()->subDays($trim)->addDays($i)->startOfDay();

            $this->info($now->toDateString() . " Last id: $lastId");

            $transactionsUser = Transaction::where('created_at', '<', $now->toDateTimeString())
                ->where('id', '>', $lastId)
                ->where('created_at', '>', $now->subDay()->toDateTimeString())
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
                }
                $userSum->sum = ($userSum->bets + $userSum->wins) * (100 - $casinoFit) / 100;
                $userSum->save();
                $lastId = $transaction->id;

                if ($agent_id = @User::find($userId)->agent_id and $agent = $agents->where('id', $agent_id)->first()) {
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
                        $agentSum->parent_profit = $agentSum->total_sum * ($agentSum->parent_percent - $agentSum->agent_percent) / 100;
                    }
                    $agentSum->save();
                }
            }
        }

//        foreach ($agents as $agent) {
//            $users = User::where('agent_id', $agent->id)->get();
//
//            for ($i = 0; $i < 7; $i++) {
//                $now = Carbon::now()->subDays(7)->addDays($i);
//                $nowStr = $now->toDateTimeString();
//                $nowSubStr = $now->subDay()->toDateTimeString();
//                $totalAgentSumPerDay = 0;
//
//                foreach ($users as $user) {
//                    $transactions = $user->transactions()
//                        ->select(DB::raw('sum(`sum`) as total'))
//                        ->where('transactions.sum', '<>', 0)
//                     //   ->where('agent_commission', '<>', 0)
//                        ->whereIn('type', [1, 2])
//                        ->where('created_at', '<', $nowStr)
//                        ->where('created_at', '>=', $nowSubStr)
//                        ->first();
//                    if ($transactions->total) {
//                        $trSum = new UserSum();
//                        $trSum->user_id = $user->id;
//                        $trSum->parent_id = $agent->id;
//                        $trSum->percent = $agent->koefs->koef;
//                        $trSum->sum = $transactions->total;
//                        $trSum->created_at = $now;
//                        $trSum->save();
//                        $totalAgentSumPerDay += $transactions->total;
//                    }
//                }
//                if ($totalAgentSumPerDay) {
//                    $newAgentSum = new AgentSum();
//                    $newAgentSum->user_id = $agent->id;
//                    $newAgentSum->total_sum = $totalAgentSumPerDay;
//                    $newAgentSum->agent_percent = $agent->koefs->koef;
//                    if ($agent->agent_id) {
//                        $newAgentSum->parent_percent = $agent->parentKoef->koef;
//                        $newAgentSum->parent_profit = $newAgentSum->total_sum * ($newAgentSum->parent_percent - $newAgentSum->agent_percent) / 100;
//                    }
//                    $newAgentSum->created_at = $now;
//                    $newAgentSum->save();
//                }
//            }
//        }
    }
}
