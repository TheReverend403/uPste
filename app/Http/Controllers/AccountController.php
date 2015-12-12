<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Upload;
use Auth;
use Illuminate\Mail\Message;
use Mail;
use Session;
use Storage;

class AccountController extends Controller
{
    public function getIndex()
    {
        // Check if the user has been registered for 7 days or less
        $now = time();
        $registered_date = strtotime(Auth::user()->created_at);
        $datediff = abs($now - $registered_date);
        $days_registered = 7 - round($datediff / (60 * 60 * 24));
        $new = $days_registered <= 7;

        return view('account.index', compact('new', 'days_registered'));
    }

    public function getResources()
    {
        return view('account.resources');
    }

    public function getBashScript()
    {
        return response()->view('account.resources.bash')->header('Content-Type', 'text/plain');
    }

    public function getUploads()
    {
        $uploads = Upload::whereUserId(Auth::user()->id)->orderBy('updated_at', 'desc')->paginate(15);
        return view('account.uploads', compact('uploads'));
    }

    public function postUploadsDelete(Upload $upload)
    {
        if (Auth::user()->id != $upload->user_id) {
            flash()->error('That file is not yours, you cannot delete it!');
            return redirect()->back();
        }

        if (Storage::disk()->exists("uploads/" . $upload->name)) {
            Storage::disk()->delete("uploads/" . $upload->name);
        }

        $upload->forceDelete();
        flash()->success($upload->original_name . ' has been deleted.');
        return redirect()->back();
    }

    public function postResetKey()
    {
        Auth::user()->fill(['apikey' => str_random(64)])->save();
        flash()->info('Your API key was reset. New API key: ' . Auth::user()->apikey)->important();
        Mail::queue(['text' => 'emails.user.api_key_reset'], ['user' => Auth::user()], function (Message $message) {
            $message->subject(sprintf("[%s] API Key Reset", env('DOMAIN')));
            $message->to(Auth::user()->email);
        });
        return redirect()->route('account');
    }
}
