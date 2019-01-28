<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatisticalData extends Model
{
    protected $table = 'statistical_data';
    protected $fillable = ['event_id', 'value', 'tracker_id'];
}
