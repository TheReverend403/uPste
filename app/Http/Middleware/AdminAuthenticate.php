<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Teapot\StatusCode;

class AdminAuthenticate
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
                flash()->error('You must log in to access that page.');

                return redirect()->route('login');
            }
        }

        if (!Auth::user()->admin) {
            flash()->error('You do not have permission to access that area.');

            return redirect()->back();
        }

        return $next($request);
    }
}
