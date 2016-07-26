<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Session;
use Teapot\StatusCode;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->auth->check()) {
            Auth::logout();
            Session::flush();
            flash()->error(trans('messages.not_logged_in'))->important();

            return redirect()->route('login');
        }

        if (config('upste.require_email_verification') && !$request->user()->confirmed) {
            Auth::logout();
            Session::flush();
            flash()->error(trans('messages.not_confirmed'))->important();

            return redirect()->route('login');
        }

        if (config('upste.require_user_approval') && !$request->user()->enabled) {
            Auth::logout();
            Session::flush();
            flash()->error(trans('messages.not_activated'))->important();

            return redirect()->route('login');
        }

        if ($request->user()->banned) {
            Auth::logout();
            Session::flush();
            flash()->error(trans('messages.not_activated'))->important();

            return redirect()->route('login');
        }

        return $next($request);
    }
}
