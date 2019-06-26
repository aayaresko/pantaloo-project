<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $table = 'withdraws';

    protected $fillable = ['user_id', 'value', 'transaction_id', 'to_address', 'confirmations', 'ext_id', 'extra', 'status_withdraw'];
}

