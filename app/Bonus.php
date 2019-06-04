<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    public function users()
    {
        return $this->hasMany(\App\User::class);
    }

    public function getClass()
    {
        $classes = [
            \App\Bonuses\Bonus_100::class,
            \App\Bonuses\Bonus_150::class,
            \App\Bonuses\Bonus_200::class,
            \App\Bonuses\FreeSpins::class,
        ];

        foreach ($classes as $class) {
            if ($class::$id == $this->id) {
                return $class;
            }
        }

        throw new \Exception('Class not found');
    }
}
