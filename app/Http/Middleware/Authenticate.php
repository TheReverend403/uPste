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
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', StatusCode::UNAUTHORIZED);
            } else {
                flash()->error('You must log in to access that page.')->important();

                return redirect()->route('login');
            }
        }

        if (!Auth::user()->enabled) {
            flash()->error(
                'Your account has not been approved. You will be notified via email when your account status changes.'
            )->important();
            Auth::logout();

            return redirect()->route('login');
        }

        if (Auth::user()->banned) {
            flash()->error('You are banned. Contact an admin if you believe this is an error.')->important();
            Auth::logout();

            return redirect()->route('login');
        }

        return $next($request);
    }
}
