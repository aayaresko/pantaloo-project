<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExtraUser extends Model
{
    protected $table = 'extra_users';

    protected $fillable = ['user_id', 'base_line_cpa', 'block'];
}
