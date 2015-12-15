<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use DB;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Mail;
use Validator;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->redirectPath = route('index');
        $this->loginPath = route('login');
    }

    public function postRegister(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $data = $request->all();
        $this->create($data);

        Mail::queue(['text' => 'emails.admin.new_registration'], $data, function (Message $message) use ($data) {
            $message->subject(sprintf("[%s] New User Registration", env('DOMAIN')));
            $message->to(env('OWNER_EMAIL'));
        });
        flash()->success(
            'Your account request has successfully been registered. You will receive an email when an admin accepts or rejects your request.')
            ->important();
        return redirect()->route('index');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255|unique:users|alpha_num',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new account instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        $apiKey = str_random(64);
        while (User::whereApikey($apiKey)->first()) {
            $apiKey = str_random(64);
        }

        $firstUser = DB::table('users')->count() == 0;
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'apikey' => $apiKey,
            'password' => bcrypt($data['password']),
            // First user registered should be enabled and admin
            'admin' => $firstUser,
            'enabled' => $firstUser
        ]);

        return $user;
    }
}
