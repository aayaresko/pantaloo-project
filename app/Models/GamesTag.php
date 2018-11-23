<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamesTag extends Model
{
    protected $table = 'games_tags';
    protected $fillable = ['code', 'name'];
}
