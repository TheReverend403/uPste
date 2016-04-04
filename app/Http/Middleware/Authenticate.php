<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Contracts\Auth\Guard;
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
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            Auth::logout();
            if ($request->ajax()) {
                return response('Unauthorized.', StatusCode::UNAUTHORIZED);
            } else {
                flash()->error(trans('messages.not_logged_in'))->important();

                return redirect()->route('login');
            }
        }

        if (config('upste.require_user_approval') && !Auth::user()->enabled) {
            flash()->error(trans('messages.not_activated'))->important();
            Auth::logout();

            return redirect()->route('login');
        }

        if (Auth::user()->banned) {
            flash()->error(trans('messages.banned'))->important();
            Auth::logout();

            return redirect()->route('login');
        }

        return $next($request);
    }
}
