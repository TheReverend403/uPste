<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Auth\Guard;
use Session;

class Admin
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
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                Session::flash('alert',
                    'You must log in to access that page.');
                return redirect()->route('login');
            }
        }
        if (!Auth::user()->admin) {
            Session::flash('alert',
                'You do not have permission to access that area.');
            return redirect()->back();
        }
        return $next($request);
    }
}
