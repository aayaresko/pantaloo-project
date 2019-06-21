<?php

namespace App\Models\Pantallo;

use Illuminate\Database\Eloquent\Model;

class GamesPantalloFreeRounds extends Model
{
    protected $table = 'games_pantallo_free_rounds';

    protected $fillable = ['created', 'free_round_id', 'user_id', 'game_id', 'available', 'valid_to', 'round'];
}
