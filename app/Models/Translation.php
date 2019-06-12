<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $table = 'translator_translations';
    protected $fillable = ['locale', 'namespace', 'group', 'item', 'text', 'unstable'];
}
