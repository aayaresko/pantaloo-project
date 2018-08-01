<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['short_name', 'url', 'title', 'body', 'is_main', 'extra_content'];
}
