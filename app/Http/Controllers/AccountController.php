<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Upload;
use Auth;
use Mail;
use Session;

class AccountController extends Controller
{
    public function index()
    {
        $now = time();
        $registered_date = strtotime(Auth::user()->created_at);
        $datediff = $now - $registered_date;
        $days = floor($datediff / (60*60*24));
        $new = $days == 0;

        return view('account.index', ['new' => $new]);
    }

    public function resources()
    {
        return view('account.resources');
    }

    public function bashScript()
    {
        return response()->view('account.resources.bash')->header('Content-Type', 'text/plain');
    }

    public function uploads()
    {
        $uploads = Upload::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(15);
        return view('account.uploads', compact('uploads'));
    }

    public function getResetKey() {
        Session::flash('warning', 'You cannot navigate to that URL, use the "Reset Key" button on your account page instead.');
        return redirect()->back();
    }

    public function postResetKey()
    {
        Auth::user()->fill(['apikey' => str_random(64)])->save();
        Session::flash('info', 'Your API key was reset.');
        Mail::queue(['text' => 'emails.user.api_key_reset'], ['user' => Auth::user()], function($message)
        {
            $message->subject(sprintf("[%s] API Key Reset", env('DOMAIN')));
            $message->to(Auth::user()->email);
        });
        return redirect()->route('account');
    }
}
