<?php

namespace App\Http\Controllers\Auth;

use App\Helpers;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPreferences;
use Auth;
use DB;
use Hash;
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
    protected $redirectPath = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redirectPath = route('index');
    }

    public function postRegister(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request,
                $validator
            );
        }

        $data = $request->all();
        $user = $this->create($data);

        if (config('upste.require_email_verification') && !$user->confirmed) {
            $confirmRoute = route('register.confirmation', $user->confirmation_code);
            Mail::queue(['text' => 'emails.user.account_confirmation'], compact('user', 'confirmRoute'), function (Message $message) use ($user) {
                $message->subject('Account Confirmation');
                $message->to($user->email);
            });
            flash()->info(trans('messages.confirmation_pending', ['email' => $user->email]))->important();
        } else {
            $mailData = ['user' => $user];
            if (config('upste.require_user_approval')) {
                $requestRoute = route('admin.requests');
                $mailData['requestRoute'] = $requestRoute;
                flash()->success(trans('messages.activation_pending', ['email' => $user->email]))->important();
            }

            Mail::queue(['text' => 'emails.admin.new_registration'], $mailData, function (Message $message) use ($data) {
                $message->subject('New User Registration');
                $message->to(config('upste.owner_email'));
            });
        }

        if (!config('upste.require_user_approval') && !config('upste.require_email_verification')) {
            Auth::login($user);
        }

        return redirect($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validationRules = [
            'name'     => 'required|max:255|unique:users|alpha_num',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'my_name'  => 'honeypot',
            'my_time'  => 'required|honeytime:5'
        ];

        if (config('upste.recaptcha_enabled')) {
            $validationRules['g-recaptcha-response'] = 'required|recaptcha';
        }

        return Validator::make($data, $validationRules);
    }

    /**
     * Create a new account instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        do {
            $apiKey = str_random(Helpers::API_KEY_LENGTH);
        } while (User::whereApikey($apiKey)->first());

        do {
            $confirmationCode = str_random(32);
        } while (User::whereConfirmationCode($confirmationCode)->first());

        $firstUser = DB::table('users')->count() == 0;
        $confirmed = $firstUser || !config('upste.require_email_verification');
        $enabled = $firstUser || !config('upste.require_user_approval');

        $user = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'apikey'            => $apiKey,
            'password'          => Hash::make($data['password'], ['rounds' => config('upste.password_hash_rounds')]),
            'confirmed'         => $confirmed,
            'confirmation_code' => $confirmed ? null : $confirmationCode,
            // First user registered should be enabled and admin
            'admin'             => $firstUser,
            'enabled'           => $enabled
        ]);

        UserPreferences::create(['user_id' => $user->id]);

        return $user;
    }

    public function confirm($confirmationCode)
    {
        if (!$confirmationCode) {
            flash()->error(trans('messages.no_such_confirmation_code'))->important();

            return redirect()->route('index');
        }

        $user = User::whereConfirmationCode($confirmationCode)->first();

        if (!$user) {
            flash()->error(trans('messages.no_such_confirmation_code'))->important();

            return redirect()->route('index');
        }

        $user->confirmed = true;
        $user->confirmation_code = null;
        $user->save();

        $mailData = ['user' => $user];
        if (config('upste.require_user_approval')) {
            $requestRoute = route('admin.requests');
            $mailData['requestRoute'] = $requestRoute;
            flash()->info(trans('messages.activation_pending', ['email' => $user->email]))->important();
        }

        Mail::queue(['text' => 'emails.admin.new_registration'], $mailData, function (Message $message) {
            $message->subject('New User Registration');
            $message->to(config('upste.owner_email'));
        });

        return redirect()->route('login');
    }

    public function authenticated(Request $request, User $user)
    {
        if (Hash::needsRehash($user->password, ['rounds' => config('upste.password_hash_rounds')])) {
            $user->password = Hash::make($request->input('password'), ['rounds' => config('upste.password_hash_rounds')]);
            $user->save();
        }

        return redirect()->intended($this->redirectPath());
    }
}
