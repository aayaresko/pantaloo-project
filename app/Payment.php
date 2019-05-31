<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public function getStatus()
    {
        if ($this->status == 0) {
            return '<span class="label label-warning">In process</span>';
        } elseif ($this->status == 1) {
            return '<span class="label label-success">Complete</span>';
        } else {
            return '<span class="label label-danger">Blocked</span>';
        }
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
