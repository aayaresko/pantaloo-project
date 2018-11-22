<?php

namespace App\Models\Pantallo;

use Illuminate\Database\Eloquent\Model;

class GamesPantalloSessionGame extends Model
{
    protected $table = 'games_pantallo_session_game';
    protected $fillable = ['session_id', 'gamesession_id'];
}
