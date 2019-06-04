<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = 'translator_languages';
    protected $fillable = ['locale', 'name'];
}
