<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamesTagGame extends Model
{
    protected $table = 'games_types_games';
    protected $fillable = ['game_id', 'type_id', 'extra'];
}
