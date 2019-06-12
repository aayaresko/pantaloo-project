<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BonusLog extends Model
{
    protected $table = 'bonus_logs';

    protected $fillable = ['bonus_id', 'operation_id', 'status'];
}
