<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamesListSettings extends Model
{
    protected $table = 'games_list_settings';

    protected $fillable = ['code', 'name', 'value'];
}
