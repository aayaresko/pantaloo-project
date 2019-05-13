<?php


namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Jobs\IntercomCreateUpdateUser;
use App\Providers\Intercom\Intercom;
use App\User;
use App\Http\Controllers\Controlleruse;
use Illuminate\Support\Facades\Auth;

class IntercomController extends Controller
{
    public function update()
    {
        $user = Auth::user();

        if ($user) {
            dispatch(new IntercomCreateUpdateUser($user));
            return 1;
        } else {
            return 0;
        }
    }
}