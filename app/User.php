<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
        if(!$this->last_activity) return false;

        $now = Carbon::now();

        if($now->format('U') - $this->last_activity->format('U') < 60) return true;
        else return false;
    }

    public function getBtcBalance()
    {
        return bcdiv($this->balance, 1000, 8);
    }

    public function getBalance()
    {
        return round($this->balance + $this->bonus_balance, 5);
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
        if($this->agent_id)
        {
            $user = User::where('id', $this->agent_id)->first();

            if($user)
            {
                return $user;
            }
        }

        return false;
    }

    public function getAgentCommission()
    {
        if($user = $this->getAgent())
        {
            if(is_numeric($user->commission)) {
                return $user->commission;
            }
        }

        return 0;
    }

    public function getAgentTotal()
    {
        $profit = 0;

        $from = Carbon::createFromFormat("U", 24*3600*2);

        $to = Carbon::now();
        $to->modify('-1 day');

        $users = User::where('agent_id', $this->id)->get();

        $result = collect();

        foreach ($users as $user) {
            $stat = $user->stat($from, $to);
            $profit = $profit + $stat['profit'];
        }

        return $profit;
    }

    public function getAgentAvailable()
    {
        $profit = $this->getAgentTotal();

        $available = $profit - $this->payments()->sum('sum');

        if($available <= 0) return 0;

        return round($available, 5, PHP_ROUND_HALF_DOWN);
    }

    public function changeBalance($transaction, $cancel = false)
    {
        $transaction->sum = round($transaction->sum, 5, PHP_ROUND_HALF_DOWN);
        $transaction->bonus_sum = round($transaction->bonus_sum, 5, PHP_ROUND_HALF_DOWN);

        if($transaction->user_id != $this->id) throw new \Exception('Invalid user id');

        if($agent = $this->getAgent())
        {
            $transaction->agent_commission = $agent->commission;
            $transaction->agent()->associate($agent);
        }

        if(!$transaction->sum) $transaction->sum = 0;
        if(!$transaction->bonus_sum) $transaction->bonus_sum = 0;
        if(!$transaction->free_spin) $transaction->free_spin = 0;

        $data = [
            'sum' => $transaction->sum,
            'bonus_sum' => $transaction->bonus_sum,
            'free_spin' => $transaction->free_spin,
            'user_id' => $this->id,
            'transaction' => $transaction,
            'cancel' => $cancel
        ];

        $res = DB::transaction(function($data) use ($data)
        {
            $sum = $data['sum'];
            $user_id = $data['user_id'];
            $bonus_sum = $data['bonus_sum'];
            $transaction = $data['transaction'];
            $cancel = $data['cancel'];
            $free_spin = $data['free_spin'];

            if($cancel)
            {
                if(!$transaction->id) throw new \Exception('Transaction id not found');

                DB::update('UPDATE users SET balance=balance + :sum, bonus_balance = bonus_balance + :bonus_sum WHERE id=:id', ['sum' => -1*$transaction->sum, 'id' => $user_id, 'bonus_sum' => -1*$transaction->bonus_sum]);
                $transaction->delete();

                $results = DB::select('select balance, bonus_balance from users where id = :id', ['id' => $user_id]);
                $balance = $results[0]->balance;
                $bonus_balance = $results[0]->bonus_balance;
                $spins_balance = 0;
            }
            else {
                $results = DB::select('select balance, bonus_balance, free_spins from users where id = :id', ['id' => $user_id]);
                $balance = $results[0]->balance;
                $bonus_balance = $results[0]->bonus_balance;
                $spins_balance = $results[0]->free_spins;

                if ($balance + $sum >= 0 and $bonus_balance + $bonus_sum >= 0 and $spins_balance + $free_spin >= 0) {
                    DB::update('UPDATE users SET balance=balance + :sum, bonus_balance = bonus_balance + :bonus_sum, free_spins = free_spins + :free_spin WHERE id=:id', ['sum' => $sum, 'id' => $user_id, 'bonus_sum' => $bonus_sum, 'free_spin' => $free_spin]);
                } else throw new \Exception('Not enough funds');

                $results = DB::select('select balance, bonus_balance, free_spins from users where id = :id', ['id' => $user_id]);
                $balance = $results[0]->balance;
                $bonus_balance = $results[0]->bonus_balance;
                $spins_balance = $results[0]->free_spins;

                if ($balance < 0) throw new \Exception('Not enough funds');
                if ($bonus_balance < 0) throw new \Exception('Not enough funds');
                if ($spins_balance < 0) throw new \Exception('Not enough funds');

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
        if($this->country) return $this->country;
        else return '-';
    }

    public function isConfirmed()
    {
        if($this->email_confirmed == 1) return true;
        else return false;
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    public function bonus()
    {
        return $this->belongsTo('App\Bonus');
    }

    public function bonuses()
    {
        return $this->hasMany('App\UserBonus');
    }

    public function payments()
    {
        return $this->hasMany('App\Payment');
    }

    public function stat(Carbon $from, Carbon $to)
    {
        $stat = [
            'deposits' => 0,
            'bets' => 0,
            'bet_count' => 0,
            'avg_bet' => 0,
            'wins' => 0,
            'revenue' => 0,
            'bonus' => 0,
            'profit' => 0,
            'adminProfit' => 0
        ];

        $transactions = $this->transactions()->where('created_at', '>=', $from)->where('created_at', '<=', $to)->get();

        foreach ($transactions as $transaction)
        {
            if($transaction->type == 3)
            {
                $stat['deposits'] = $stat['deposits'] + $transaction->sum;
            }
            elseif($transaction->type == 1 or $transaction->type == 2)
            {
                if($transaction->type == 1)
                {
                    $stat['bets'] = $stat['bets'] + (-1)*$transaction->sum;
                    $stat['bet_count'] = $stat['bet_count'] + 1;
                }
                else
                {
                    $stat['wins'] = $stat['wins'] + $transaction->sum;
                }

                $stat['revenue'] = $stat['revenue'] + (-1)*$transaction->sum;
                $stat['bonus'] = $stat['bonus'] + $transaction->bonus_sum;

                $stat['profit'] = $stat['profit'] + (-1)*$transaction->sum*$transaction->agent_commission/100;
                $stat['adminProfit'] = $stat['adminProfit'] + (-1)*$transaction->sum - (-1)*$transaction->sum*$transaction->agent_commission/100;
            }
        }

        if($stat['bet_count'] != 0)
            $stat['avg_bet'] = $stat['bets']/$stat['bet_count'];

        return $stat;
    }

    public function isAdmin()
    {
        if($this->role >= 2) return true;
        else return false;
    }

    public function isAgent()
    {
        if($this->role >= 1) return true;
        else return false;
    }

    public function trackers()
    {
        return $this->hasMany('App\Tracker');
    }

    public function tracker()
    {
        return $this->belongsTo('App\Tracker');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }

    public function agentTransactions()
    {
        return $this->hasMany('App\Transaction', 'agent_id');
    }
}
