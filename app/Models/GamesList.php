<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamesList extends Model
{
    protected $table = 'games_list';
    protected $fillable = ['system_id', 'name', 'our_name', 'type_id', 'category_id', 'details', 'mobile', 'image', 'our_image', 'image_preview',
        'image_filled', 'image_background', 'rating', 'provider_id', 'active', 'free_round'];


    public function types()
    {
        return $this->belongsToMany('App\Models\GamesType', 'games_types_games', 'game_id', 'type_id');
    }
}
