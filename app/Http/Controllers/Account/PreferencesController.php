<?php

namespace App\Http\Controllers\Account;

use App\Http\Requests;
use Auth;
use Illuminate\Http\Request;
use Teapot\StatusCode;
use Validator;

class PreferencesController extends AccountController
{
    public function get(Request $request)
    {
        return view('account.preferences', ['user_preferences' => $request->user()->preferences]);
    }

    public function post(Request $request)
    {
        $rules = [
            'timezone'         => 'required|timezone',
            'pagination-items' => 'required|min:4|max:50|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('account.preferences')
                ->withErrors($validator);
        }

        $request->user()->preferences->fill([
            'user_id'          => Auth::id(),
            'timezone'         => $request->input('timezone'),
            'pagination_items' => $request->input('pagination-items'),
        ])->save();

        flash()->success(trans('messages.preferences_saved'));

        return redirect()->route('account.preferences');
    }
}
