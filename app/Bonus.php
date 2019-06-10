<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function getClass()
    {
        $classes = [
            'App\Bonuses\Bonus_100',
            'App\Bonuses\Bonus_150',
            'App\Bonuses\Bonus_200',
            'App\Bonuses\FreeSpins'
        ];

        foreach ($classes as $class)
        {
            if($class::$id == $this->id) return $class;
        }

        throw new \Exception('Class not found');
    }

    public function activeBonus()
    {
        return $this->hasOne('App\UserBonus', 'bonus_id', 'id');
    }
}
