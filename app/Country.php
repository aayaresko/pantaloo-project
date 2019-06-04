<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['code', 'name'];

    public function user()
    {
        return $this->belongsToMany('App\User', 'affiliate_countries');
    }
}
