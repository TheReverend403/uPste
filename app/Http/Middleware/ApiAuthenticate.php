<?php

namespace App\Http\Middleware;

use App\Models\User;
use Auth;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Teapot\StatusCode;

class ApiAuthenticate
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
        if (!$request->has('key')) {
            return response()->json(['missing_api_key'], StatusCode::UNAUTHORIZED);
        }

        $apiKey = $request->input('key');
        $user = User::whereApikey($apiKey)->first();
        if (!$user) {
            return response()->json(['invalid_api_key'], StatusCode::UNAUTHORIZED);
        }

        if (config('upste.require_email_verification') && !$user->confirmed) {
            return response()->json(['email_not_confirmed'], StatusCode::UNAUTHORIZED);
        }

        if (config('upste.require_user_approval') && !$user->enabled) {
            return response()->json(['account_not_approved'], StatusCode::UNAUTHORIZED);
        }

        if ($user->banned) {
            return response()->json(['user_banned'], StatusCode::UNAUTHORIZED);
        }

        Auth::onceUsingId($user->id);

        return $next($request);
    }
}
