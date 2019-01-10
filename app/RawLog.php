<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RawLog extends Model
{
    protected $table = 'raw_log';

    protected $fillable = ['response', 'request', 'type_id', 'extra'];
}
