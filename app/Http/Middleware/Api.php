<?php

namespace App\Http\Middleware;

use App\User;
use Auth;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Input;

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
        if (!Input::has('key')) {
            return response()->json(["message" => "Missing API key", 'code' => 401]);
        }

        $apikey = Input::get('key');
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

        Auth::onceUsingId($user->id);
        return $next($request);
    }
}
