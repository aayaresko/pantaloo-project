<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function slots()
    {
        return $this->hasMany(\App\Slot::class);
    }
}
