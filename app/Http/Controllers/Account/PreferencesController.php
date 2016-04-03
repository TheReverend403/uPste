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
        $validator = Validator::make($request->all(), [
            'timezone'         => 'required|timezone',
            'pagination-items' => 'required|min:3|max:100|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.preferences')
                ->withErrors($validator);
        }

        Auth::user()->preferences->fill([
            'user_id'          => Auth::id(),
            'timezone'         => $request->input('timezone'),
            'pagination_items' => $request->input('pagination-items'),
        ])->save();

        flash()->success(trans('messages.preferences_saved'));
        return redirect()->route('account.preferences');
    }
}
