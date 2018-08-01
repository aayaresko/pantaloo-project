<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PublicToken extends Model
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

    public function getToken()
    {
        $token = new Token();
        $token->generate($this->user);
        $token->user()->associate($this->user);
        $token->slot()->associate($this->slot);
        $token->save();

        return $token;
    }
}
