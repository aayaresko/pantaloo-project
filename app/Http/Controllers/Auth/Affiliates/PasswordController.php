<?php

namespace App\Http\Controllers\Auth\Affiliates;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends Controller
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

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->toArray(), [
            'email' => 'required|email'
        ]);
        $errors = [];
        if ($validator->fails()) {
            $validatorErrors = $validator->errors()->toArray();
            array_walk_recursive($validatorErrors, function ($item, $key) use (&$errors) {
                array_push($errors, $item);
            });
            return response()->json([
                'status' => false,
                'message' => [
                    'errors' => $errors
                ]
            ]);
        }

        $broker = $this->getBroker();

        $response = Password::broker($broker)->sendResetLink(
            $this->getSendResetLinkEmailCredentials($request),
            $this->resetEmailBuilder()
        );

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return response()->json([
                    'status' => true,
                    'message' => [
                        'response' => $response,
                    ]
                ]);
            case Password::INVALID_USER:
            default:
                return response()->json([
                    'status' => false,
                    'message' => [
                        'response' => $response,
                    ]
                ]);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $this->validate(
            $request,
            $this->getResetValidationRules(),
            $this->getResetValidationMessages(),
            $this->getResetValidationCustomAttributes()
        );

        $credentials = $this->getResetCredentials($request);

        $broker = $this->getBroker();

        $response = Password::broker($broker)->reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                return response()->json([
                    'status' => true,
                    'message' => [
                        'response' => $response,
                    ]
                ]);
            default:
                return response()->json([
                    'status' => false,
                    'message' => [
                        'response' => $response,
                    ]
                ]);
        }
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
