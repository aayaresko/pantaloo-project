<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GamesPantalloSession extends Model
{
    protected $table = 'games_pantallo_session';

    protected $fillable = ['user_id', 'system_id', 'username', 'balance', 'currencycode', 'created', 'agent_balance', 'sessionid'];
}
