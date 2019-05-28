<?php

namespace App\Exceptions;

use Exception;
use Helpers\GeneralHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        $appDebug = is_null(config('app.debug')) ? true : config('app.debug');
        
        if (!$appDebug) {
            if ($this->isHttpException($e)) {
                if ($e->getStatusCode() == 404) {
                    return response()->view('errors.' . '404', [], 404);
                }
            }

            if (app()->bound('sentry') && $this->shouldReport($e)) {
                if (Auth::check()) {
                    Sentry\configureScope(function (Sentry\State\Scope $scope): void {
                        $user = Auth::user();
                        $scope->setUser([
                            'id' => $user->id,
                            'email' => $user->email,
                            'ip_address' => GeneralHelper::visitorIpCloudFlare()
                        ]);
                    });
                } else {

                }
                app('sentry')->captureException($e);
            }

            if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                return abort('404');
            }

//        if (function_exists('appoptics_log_exception')) {
//            appoptics_log_exception('app', $e);
//        }
        }

        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof \Illuminate\Session\TokenMismatchException) {
            return redirect()
                ->back()
                //->withInput($request->except('password', '_token'))
                ->withErrors('You have been inactive for too long, please reload the page.');
        }
        
        if ($this->shouldReport($e) && !$this->isHttpException($e) && !config('app.debug')) {
            $e = new HttpException(500, 'Whoops!');
        }

        return parent::render($request, $e);
    }
}
