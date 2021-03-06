<?php

namespace App;

use App\Bonuses\Bonus_100;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserBonus extends Model
{
    use SoftDeletes;

    protected $casts = [
        'data' => 'array',
    ];

    protected $fillable = ['activated', 'expires_at', 'user_id', 'bonus_id', 'data', 'total_amount', 'ip_address'];

    protected $dates = [
        'created_at', 'updated_at', 'expires_at',
    ];

    protected $table = 'user_bonuses';

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function bonus()
    {
        return $this->belongsTo(\App\Bonus::class);
    }

    public function getObject()
    {
        return Bonus_100::hydrate([(array) $this])[0];
    }
}
