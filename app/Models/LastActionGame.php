<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LastActionGame extends Model
{
    protected $table = 'last_action_games';

    protected $fillable = ['user_id', 'game_id', 'last_action', 'last_game', 'gamesession_id', 'number_games'];
}
