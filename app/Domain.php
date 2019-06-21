<?php

namespace App;

use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    public static function getLang()
    {
        $url = Request::fullUrl();

        echo $url;
        exit;
    }
}
