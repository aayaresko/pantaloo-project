<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Token extends Model
{
    public function slot()
    {
        return $this->belongsTo('App\Slot');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function generate($user = false)
    {
        if(!$user) $user = Auth::user();

        $this->token = uniqid() . rand() . '-' . substr(md5(rand() . $user->id . $user->email), 0, 10);
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }
}
