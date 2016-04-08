<?php

namespace App\Http\Controllers\Account;

use App\Http\Requests;
use Auth;
use Illuminate\Http\Request;
use Teapot\StatusCode;
use Validator;

class PreferencesController extends AccountController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get()
    {
        return view('account.preferences', ['user_preferences' => Auth::user()->preferences]);
    }

    public function post(Request $request)
    {
        $rules = [
            'timezone'         => 'required|timezone',
            'pagination-items' => 'required|min:3|max:100|integer',
            'email'            => 'required|email|max:255|unique:users',
        ];

        // Allow the validator to pass the uniqueness check if the email hasn't changed
        if ($request->input('email') === Auth::user()->email) {
            $rules['email'] = 'required|email|max:255';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('account.preferences')
                ->withErrors($validator);
        }

        Auth::user()->preferences->fill([
            'user_id'          => Auth::id(),
            'timezone'         => $request->input('timezone'),
            'pagination_items' => $request->input('pagination-items'),
        ])->save();

        Auth::user()->fill(['email' => $request->input('email')])->save();

        flash()->success(trans('messages.preferences_saved'));

        return redirect()->route('account.preferences');
    }
}
