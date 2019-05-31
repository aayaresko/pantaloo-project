<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function getAnswer()
    {
        return str_replace("\n", '<br>', $this->answer);
    }
}
