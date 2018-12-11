<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamesCategory extends Model
{
    protected $table = 'games_categories';
    protected $fillable = ['code', 'name', 'image', 'rating', 'active'];
}
