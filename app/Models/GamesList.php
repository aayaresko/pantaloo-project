<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamesList extends Model
{
    protected $table = 'games_list';
    protected $fillable = ['system_id', 'name', 'type_id', 'category_id', 'details', 'mobile', 'image', 'our_image', 'image_preview',
        'image_filled', 'image_background', 'rating', 'provider_id'];

    public function getNameAttribute($value)
    {
        if (!is_null($this->attributes['our_name'])) {
            $name = $this->attributes['our_name'];
        } else {
            $name = $this->attributes['name'];
        }
        return $name;
    }
}
