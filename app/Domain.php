<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\Request;

class Domain extends Model
{
    static function getLang()
    {
        $url = Request::fullUrl();

        echo $url;
        exit;
    }
}
