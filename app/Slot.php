<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slot extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    //protected $hidden = ['password'];

    public function tokens()
    {
        return $this->hasMany('App\Token');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function type()
    {
        return $this->belongsTo('App\Type');
    }
}
