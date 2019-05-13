<?php

namespace App\Console\Commands;

use App\Models\AgentsKoef;
use App\Models\AgentSum;
use App\Models\UserSum;
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
        $agents = User::whereIn('role', [1, 3])->get();
        foreach ($agents as $agent) {
            $newAgent = AgentsKoef::where('user_id', $agent->id)->first();
            if (!$newAgent) {
                $newAgent = new AgentsKoef();
                $newAgent->user_id = $agent->id;
                $newAgent->koef = 0;
                $newAgent->save();
            }

            $users = User::where('agent_id', $agent->id)->get();

            for ($i = 0; $i < 7; $i++) {
                $now = Carbon::now()->subDays(7)->addDays($i);
                $nowStr = $now->toDateTimeString();
                $nowSubStr = $now->subDay()->toDateTimeString();
                $totalAgentSumPerDay = 0;

                foreach ($users as $user) {
                    $transactions = $user->transactions()
                        ->select(DB::raw('sum(`sum`) as total'))
                        ->where('transactions.sum', '<>', 0)
                     //   ->where('agent_commission', '<>', 0)
                        ->whereIn('type', [1, 2])
                        ->where('created_at', '<', $nowStr)
                        ->where('created_at', '>=', $nowSubStr)
                        ->first();
                    if ($transactions->total) {
                        $trSum = new UserSum();
                        $trSum->user_id = $user->id;
                        $trSum->parent_id = $agent->id;
                        $trSum->sum = $transactions->total;
                        $trSum->created_at = $now;
                        $trSum->save();
                        $totalAgentSumPerDay += $transactions->total;
                    }
                }
                if ($totalAgentSumPerDay) {
                    $newAgentSum = new AgentSum();
                    $newAgentSum->user_id = $agent->id;
                    $newAgentSum->total_sum = $totalAgentSumPerDay;
                    $newAgentSum->agent_percent = $agent->koefs->koef;
                    if ($agent->agent_id) {
                        $newAgentSum->parent_percent = $agent->parentKoef->koef;
                        $newAgentSum->parent_profit = $newAgentSum->total_sum * ($newAgentSum->parent_percent - $newAgentSum->agent_percent) / 100;
                    }
                    $newAgentSum->created_at = $now;
                    $newAgentSum->save();
                }
            }
        }
    }
}
