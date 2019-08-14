<?php

namespace App\Http\Controllers\Auth\Affiliates;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\View;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function sendResetLinkResponse($request, $response)
    {
        return response()->json([
            'status' => true,
            'message' => [
                'response' => $response,
                'email' => $request->email,
                'title' => 'Reset Password',
                'body' => (string) view('affiliates.parts.reset_password')->with(['email' => $request->email]),
            ],
        ]);
    }

    protected function sendResetLinkFailedResponse($request, $response)
    {
        return response()->json([
            'status' => false,
            'message' => [
                'response' => $response,
                'errors' => ['User with such e-mail is not registered'],
            ],
        ]);
    }
}
