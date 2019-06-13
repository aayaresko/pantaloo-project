<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModernExtraUsers extends Model
{
    protected $table = 'modern_extra_users';

    protected $fillable = ['user_id', 'code', 'value'];
}
