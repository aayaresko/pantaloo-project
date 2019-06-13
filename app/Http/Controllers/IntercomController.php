<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\Request;
use App\Providers\Intercom\Intercom;
use Illuminate\Support\Facades\Auth;
use App\Jobs\IntercomCreateUpdateUser;
use App\Http\Controllers\Controlleruse;

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
