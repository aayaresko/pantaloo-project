<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Tracker extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function getLinks()
    {
        $domains = Domain::all();
        $links = collect([]);
        foreach ($domains as $domain)
        {
            $links->push('http://' . $domain->domain . '/?ref=' . $this->ref);
        }

        return $links;
    }

    public function stat(Carbon $from, Carbon $to)
    {
        $result = [
            'deposits' => 0,
            'pending_deposits' => 0,
            'confirm_deposits' => 0,
            'bets' => 0,
            'bet_count' => 0,
            'avg_bet' => 0,
            'wins' => 0,
            'revenue' => 0,
            'bonus' => 0,
            'profit' => 0
        ];

        foreach ($this->users as $user)
        {
            $stat = $user->stat($from, $to);

            foreach ($stat as $key => $value)
            {
                if(!isset($result[$key])) $result[$key] = 0;
                $result[$key] = $result[$key] + $value;
            }
        }

        return $result;
    }
}
