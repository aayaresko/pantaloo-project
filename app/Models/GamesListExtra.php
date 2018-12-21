<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamesListExtra extends Model
{
    protected $table = 'games_list_extra';
    protected $fillable = ['game_id', 'type_id', 'category_id'];

}
