<?php

namespace App\Http\Controllers\Auth\Affiliates;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendResetResponse($request, $response)
    {
        return response()->json([
            'status' => true,
            'message' => [
                'response' => $response,
                'redirect' => '/affiliates/dashboard',
            ],
        ]);
    }

    public function sendResetFailedResponse($request, $response)
    {
        return response()->json([
            'status' => false,
            'message' => [
                'errors' => 'WTF',
            ],
        ]);
    }

    /**
     * @param Request $request
     * @param null $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        if (is_null($token)) {
            return redirect()->route('affiliates.index');
        }

        $email = $request->input('email');

        return view('affiliates.reset_password')->with(compact('token', 'email'));
    }
}
