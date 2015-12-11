<?php

namespace App\Exceptions;

use Auth;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Mail;
use Session;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        TokenMismatchException::class,
        HttpException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        $data = [
            'ip' => request()->getClientIp(),
            'url' => request()->fullUrl(),
            'exception' => $e
        ];

        if ($this->shouldReport($e)) {
            Mail::send(['text' => 'emails.admin.exception'], $data, function ($message) {
                $message->subject(sprintf("[%s] Application Exception", env('DOMAIN')));
                $message->to(env('OWNER_EMAIL'));
            });
        }

        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof TokenMismatchException) {
            Session::flash('alert', 'CSRF verification failed, try logging in again.');
            Auth::logout();
            return redirect()->route('login');
        }

        return parent::render($request, $e);
    }
}
