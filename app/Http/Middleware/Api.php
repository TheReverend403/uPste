<?php

namespace App\Http\Middleware;

use App\User;
use Auth;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class Api
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
        $apikey = $request->get('key');
        if (!$apikey) {
            return response()->json(["message" => "Missing API key", 'code' => 401]);
        }

        $user = User::where('apikey', $apikey)->first();
        if (!$user) {
            return response()->json(["message" => "Invalid API key", 'code' => 401]);
        }

        if (!$user->enabled) {
            return response()->json(["message" => "Your account has not been approved by an admin", 'code' => 401]);
        }

        if ($user->banned) {
            return response()->json(["message" => "You are banned", 'code' => 401]);
        }

        Auth::login($user);
        return $next($request);
    }
}
