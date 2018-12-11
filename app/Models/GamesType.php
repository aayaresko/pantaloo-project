<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamesType extends Model
{
    protected $table = 'games_types';
    protected $fillable = ['code', 'name', 'image', 'rating', 'active'];
}
