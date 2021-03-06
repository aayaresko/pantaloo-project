<?php

namespace App;

use Carbon\Carbon;
use App\Mail\BaseMailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'balance', 'bonus_balance', 'commission', 'bonus_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['created_at', 'updated_at', 'last_activity'];

    public function isOnline()
    {
        if (! $this->last_activity) {
            return false;
        }

        $now = Carbon::now();

        if ($now->format('U') - $this->last_activity->format('U') < 60) {
            return true;
        } else {
            return false;
        }
    }

    public function getBtcBalance()
    {
        return bcdiv($this->balance, 1000, 8);
    }

    public function getBalance($precision = 5)
    {
        return round($this->balance + $this->bonus_balance, $precision);
    }

    public function getRealBalance()
    {
        return round($this->balance, 5);
    }

    public function getBonusBalance()
    {
        return round($this->bonus_balance, 5);
    }

    public function getAgent()
    {
        if ($this->agent_id) {
            $user = self::where('id', $this->agent_id)->first();

            if ($user) {
                return $user;
            }
        }

        return false;
    }

    public function getAgentCommission()
    {
        if ($user = $this->getAgent()) {
            if (is_numeric($user->commission)) {
                return $user->commission;
            }
        }

        return 0;
    }

    public function getAgentTotal()
    {
        $profit = 0;

        $from = Carbon::createFromFormat('U', 24 * 3600 * 2);

        $to = Carbon::now();
        $to->modify('-1 day');

        $users = self::where('agent_id', $this->id)->get();

        $result = collect();

        foreach ($users as $user) {
            $stat = $user->stat($from, $to);
            $profit = $profit + $stat['profit'];
        }

        return $profit;
    }

    public function getAgentProfit()
    {
        $profit = 0;

        $users = self::where('agent_id', $this->id)->get();

        foreach ($users as $user) {
            $stat = $user->getUserAgentProfit();
            $profit = $profit + $stat;
        }

        return $profit;
    }

    public function getUserAgentProfit()
    {
        $stat = 0;

        $transactions = $this->transactions()
            ->select(DB::raw('sum(`sum`) as total'), 'agent_commission')
            ->where('transactions.sum', '<>', 0)
            ->where('agent_commission', '<>', 0)
            ->whereIn('type', [1, 2])
            ->groupBy('agent_commission')
            ->get();

        foreach ($transactions as $transaction) {
            $stat = $stat + (-1) * $transaction->total * $transaction->agent_commission / 100;
        }

        return $stat;
    }

    public function getAgentAvailable()
    {
        $profit = $this->getAgentProfit();

        $available = $profit - $this->payments()->sum('sum');

        if ($available <= 0) {
            return 0;
        }

        return round($available, 5, PHP_ROUND_HALF_DOWN);
    }

    //method is no longer supported!!!!!!!!!!!!!!!!!!!!!
    public function changeBalance($transaction, $cancel = false)
    {
        $transaction->sum = round($transaction->sum, 5, PHP_ROUND_HALF_DOWN);
        $transaction->bonus_sum = round($transaction->bonus_sum, 5, PHP_ROUND_HALF_DOWN);

        if ($transaction->user_id != $this->id) {
            throw new \Exception('Invalid user id');
        }

        if ($agent = $this->getAgent()) {
            $transaction->agent_commission = $agent->commission;
            $transaction->agent()->associate($agent);
        }

        if (! $transaction->sum) {
            $transaction->sum = 0;
        }
        if (! $transaction->bonus_sum) {
            $transaction->bonus_sum = 0;
        }
        if (! $transaction->free_spin) {
            $transaction->free_spin = 0;
        }

        $data = [
            'sum' => $transaction->sum,
            'bonus_sum' => $transaction->bonus_sum,
            'free_spin' => $transaction->free_spin,
            'user_id' => $this->id,
            'transaction' => $transaction,
            'cancel' => $cancel,
        ];

        $res = DB::transaction(function () use ($data) {
            $sum = $data['sum'];
            $user_id = $data['user_id'];
            $bonus_sum = $data['bonus_sum'];
            $transaction = $data['transaction'];
            $cancel = $data['cancel'];
            $free_spin = $data['free_spin'];

            if ($cancel) {
                if (! $transaction->id) {
                    throw new \Exception('Transaction id not found');
                }

                DB::update('UPDATE users SET balance=balance + :sum, bonus_balance = bonus_balance + :bonus_sum WHERE id=:id', ['sum' => -1 * $transaction->sum, 'id' => $user_id, 'bonus_sum' => -1 * $transaction->bonus_sum]);
                $transaction->delete();

                $results = DB::select('select balance, bonus_balance from users where id = :id', ['id' => $user_id]);
                $balance = $results[0]->balance;
                $bonus_balance = $results[0]->bonus_balance;
                $spins_balance = 0;
            } else {
                $results = DB::select('select balance, bonus_balance, free_spins from users where id = :id', ['id' => $user_id]);
                $balance = $results[0]->balance;
                $bonus_balance = $results[0]->bonus_balance;
                $spins_balance = $results[0]->free_spins;

                if ($balance + $sum >= 0 and $bonus_balance + $bonus_sum >= 0 and $spins_balance + $free_spin >= 0) {
                    DB::update('UPDATE users SET balance=balance + :sum, bonus_balance = bonus_balance + :bonus_sum, free_spins = free_spins + :free_spin WHERE id=:id', ['sum' => $sum, 'id' => $user_id, 'bonus_sum' => $bonus_sum, 'free_spin' => $free_spin]);
                } else {
                    throw new \Exception('Not enough funds');
                }

                $results = DB::select('select balance, bonus_balance, free_spins from users where id = :id', ['id' => $user_id]);
                $balance = $results[0]->balance;
                $bonus_balance = $results[0]->bonus_balance;
                $spins_balance = $results[0]->free_spins;

                if ($balance < 0) {
                    throw new \Exception('Not enough funds');
                }
                if ($bonus_balance < 0) {
                    throw new \Exception('Not enough funds');
                }
                if ($spins_balance < 0) {
                    throw new \Exception('Not enough funds');
                }

                $transaction->save();
            }

            return [$balance, $bonus_balance, $transaction, $spins_balance];
        });

        $this->balance = $res[0];
        $this->bonus_balance = $res[1];
        $this->free_spins = $res[3];

        $transaction = $res[2];

        return $transaction;
    }

    public function getCountry()
    {
        if ($this->country) {
            return $this->country;
        } else {
            return '-';
        }
    }

    public function isConfirmed()
    {
        if ($this->email_confirmed == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function transactions()
    {
        return $this->hasMany(\App\Transaction::class);
    }

    public function bonus()
    {
        return $this->belongsTo(\App\Bonus::class);
    }

    public function bonuses()
    {
        return $this->hasMany(\App\UserBonus::class);
    }

    public function payments()
    {
        return $this->hasMany(\App\Payment::class);
    }

    public function stat(Carbon $from, Carbon $to)
    {
        $minConfirmBtc = config('appAdditional.minConfirmBtc');

        $stat = [
            'deposits' => 0,
            'pending_deposits' => 0,
            'confirm_deposits' => 0,
            'bets' => 0,
            'bet_count' => 0,
            'avg_bet' => 0,
            'wins' => 0,
            'revenue' => 0,
            'bonus' => 0,
            'profit' => 0,
            'adminProfit' => 0,
        ];

        $transactions = $this->transactions()->where('created_at', '>=', $from)->where('created_at', '<=', $to)->get();
        //to do fix this
        $deposit = $this->transactions()->where('type', 3)->count();

        foreach ($transactions as $transaction) {
            if ($transaction->type == 3) {
                if ((int) $transaction->confirmations < $minConfirmBtc) {
                    $stat['pending_deposits'] = $stat['pending_deposits'] + $transaction->sum;
                } else {
                    $stat['confirm_deposits'] = $stat['confirm_deposits'] + $transaction->sum;
                }

                $stat['deposits'] = $stat['deposits'] + $transaction->sum;
            } elseif ($transaction->type == 1 or $transaction->type == 2) {
                if ($transaction->type == 1) {
                    $stat['bets'] = $stat['bets'] + (-1) * $transaction->sum;
                    $stat['bet_count'] = $stat['bet_count'] + 1;
                } else {
                    $stat['wins'] = $stat['wins'] + $transaction->sum;
                }

                $stat['bonus'] = $stat['bonus'] + $transaction->bonus_sum;

                $stat['revenue'] = $stat['revenue'] + (-1) * $transaction->sum;
                $stat['profit'] = $stat['profit'] + (-1) * $transaction->sum * $transaction->agent_commission / 100;

                $stat['adminProfit'] = $stat['adminProfit'] + (-1) * $transaction->sum - (-1) * $transaction->sum * $transaction->agent_commission / 100;
            }
        }

        if ($deposit == 0) {
            $stat['revenue'] = 0;
            $stat['profit'] = 0;
        }

        if ($stat['bet_count'] != 0) {
            $stat['avg_bet'] = $stat['bets'] / $stat['bet_count'];
        }

        return $stat;
    }

    public function isAdmin()
    {
        if ($this->role >= 2) {
            return true;
        } else {
            return false;
        }
    }

    public function isAgent()
    {
        if ($this->role >= 1) {
            return true;
        } else {
            return false;
        }
    }

    public function trackers()
    {
        return $this->hasMany(\App\Tracker::class);
    }

    public function tracker()
    {
        return $this->belongsTo(\App\Tracker::class);
    }

    public function currency()
    {
        return $this->belongsTo(\App\Currency::class);
    }

    public function agentTransactions()
    {
        return $this->hasMany(\App\Transaction::class, 'agent_id');
    }

    public function sendPasswordResetNotification($token)
    {
        if ($this->role == 1) {
            // affiliate
            $template = 'emails.partner.password';
        } else {
            // user, admin, etc
            $template = 'auth.emails.password';
        }

        $mail = new BaseMailable($template, ['token' => $token, 'user' => $this]);
        $mail->subject('Password reset');

        if (Mail::to($this->getEmailForPasswordReset())->send($mail)) {
            return Password::RESET_LINK_SENT;
        }
    }

    public function koefs()
    {
        return $this->hasOne('App\Models\AgentsKoef')->orderBy('id', 'desc');
    }

    public function allKoefs()
    {
        return $this->hasMany('App\Models\AgentsKoef')->orderBy('id', 'desc');
    }

    public function playersCount()
    {
        return self::where('agent_id', $this->id)->where('role', 0)->count();
    }

    public function agentsCount()
    {
        return self::where('agent_id', $this->id)->where('role', 1)->count();
    }

    public function benefits()
    {
        return $this->hasMany('App\Models\AgentSum');
    }

    public function allBenefits()
    {
        $sum = 0;
        foreach (self::where('agent_id', $this->id)->where('role', 1)->get() as $child) {
            $newSum = $child->allBenefits();
            $sum += $newSum;
        }
        $sum += $this->benefits->sum('total_sum');

        return $sum;
    }

    public function playersTotalCount()
    {
        $count = 0;
        foreach (self::where('agent_id', $this->id)->where('role', 1)->get() as $child) {
            $count += $child->playersTotalCount();
        }
        $count += $this->playersCount();

        return $count;
    }

    public function parentKoef()
    {
        return $this->hasOne('App\Models\AgentsKoef', 'user_id', 'agent_id')->orderBy('id', 'desc');
    }

    //agent profit for affiliate
    public function profit($from = false, $to = false)
    {
        $totalProfit = 0;
        $prepareQuerry = $this->benefits();
        if ($from) {
            $prepareQuerry->where('created_at', '>=', $from);
        }
        if ($to) {
            $prepareQuerry->where('created_at', '<', $to);
        }
        foreach ($prepareQuerry->get() as $benefit) {
            $totalProfit += $benefit->total_sum * ($benefit->parent_percent - $benefit->agent_percent) / 100;
        }

        return -$totalProfit;
    }
    //agent total profit for affiliate
    public function totalProfit($from = false, $to = false)
    {
        $prepareQuerry = $this->benefits();
        if ($from) {
            $prepareQuerry->where('created_at', '>=', $from);
        }
        if ($to) {
            $prepareQuerry->where('created_at', '<', $to);
        }

        return -$prepareQuerry->sum('parent_profit');
    }

    public function playerSum()
    {
        return $this->hasMany('App\Models\UserSum');
    }

    public function totalPlayerSum()
    {
        $playerSum = $this->playerSum()->sum('sum');
        return $playerSum ? -$playerSum : 0;
    }
    // agent function
    public function totalPlayerProfit()
    {
        $profit = 0;
        foreach ($this->playerSum()->get() as $playerSum) {
            $profit += $playerSum->sum * $playerSum->percent / 100;
        }

        return -$profit;
    }

    public function todayPlayerSum()
    {
         $total = $this->transactions()
            ->select(DB::raw('sum(`sum`) as total'))
            ->where('transactions.sum', '<>', 0)
            ->whereIn('type', [1, 2])
            ->where('created_at', '>=', Carbon::now()->toDateString())
            ->first();

         return $total->total ? -$total->total : 0;
    }

    public function countries()
    {
        return $this->hasOne('App\Country', 'code', 'country');
    }

    public function affiliateCountries()
    {
        return $this->belongsToMany('App\Country', 'affiliate_countries');
    }

    public function deposit()
    {
        return $this->playerSum()->sum('deposits');
    }

    public function withdraw()
    {
        $total = $this->transactions()
            ->select(DB::raw('sum(`sum`) as total'))
            ->where('type', 4)
            ->where('withdraw_status', 2)
            ->first();

        return $total->total ?: 0;
    }

    //
    public function totalEarn($from = false, $to = false)
    {
        $totalProfit = 0;
        $prepareQuerry = $this->benefits();
        if ($from) {
            $prepareQuerry->where('created_at', '>=', $from);
        }
        if ($to) {
            $prepareQuerry->where('created_at', '<', $to);
        }
        foreach ($prepareQuerry->get() as $benefit) {
            $totalProfit += $benefit->total_sum * $benefit->agent_percent / 100;
        }

        return -$totalProfit;
    }

    //
    public function totalRevenue($from = false, $to = false)
    {
        $prepareQuerry = $this->benefits();
        if ($from) {
            $prepareQuerry->where('created_at', '>=', $from);
        }
        if ($to) {
            $prepareQuerry->where('created_at', '<', $to);
        }
        $totalProfit = $prepareQuerry->sum('total_sum');

        return -$totalProfit;
    }
}
