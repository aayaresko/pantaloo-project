<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestrictionGamesCountry extends Model
{
    protected $table = 'restriction_games_by_country';

    protected $fillable = ['game_id', 'code_country', 'mark'];
}
