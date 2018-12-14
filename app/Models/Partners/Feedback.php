<?php

namespace App\Models\Partners;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'partners_feedback';
    protected $fillable = ['name', 'email', 'message'];
}
