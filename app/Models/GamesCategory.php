<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamesCategory extends Model
{
    protected $table = 'games_categories';

    protected $fillable = ['code', 'name', 'default_name', 'image', 'rating', 'active'];
}
