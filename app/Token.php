<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    public function slot()
    {
        return $this->belongsTo(\App\Slot::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function generate($user = false)
    {
        if (! $user) {
            $user = Auth::user();
        }

        $this->token = uniqid().rand().'-'.substr(md5(rand().$user->id.$user->email), 0, 10);
    }

    public function transactions()
    {
        return $this->hasMany(\App\Transaction::class);
    }
}
