<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemNotification extends Model
{
    protected $table = 'system_notifications';
    protected $fillable = ['user_id', 'type_id', 'value', 'extra', 'status'];

}
