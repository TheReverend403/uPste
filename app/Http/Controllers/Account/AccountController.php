<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Upload;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Helpers;
use Illuminate\Mail\Message;
use Mail;

class AccountController extends Controller
{
    public function getIndex()
    {
        // Check if the user has been registered for 7 days or less
        $registered = Carbon::parse(Auth::user()->created_at);
        $dateNow = Carbon::now();
        $daysRegistered = $registered->diffInDays($dateNow);
        $newUser = $daysRegistered <= Helpers::NEW_USER_DAYS;
        $daysUntilMessageHides = Helpers::NEW_USER_DAYS - round($daysRegistered / ($dateNow->diffInSeconds($dateNow->tomorrow())));

        return view('account.index', compact('newUser', 'daysUntilMessageHides'));
    }

    public function getResources()
    {
        return view('account.resources');
    }

    public function getFaq()
    {
        return view('account.faq');
    }

    public function getBashScript()
    {
        return response()->view('account.resources.bash')->header('Content-Type', 'text/plain');
    }

    public function getUploads()
    {
        $uploads = Upload::whereUserId(Auth::user()->id)->orderBy('updated_at', 'desc')->paginate(Helpers::PAGINATION_DEFAULT_ITEMS);

        return view('account.uploads', compact('uploads'));
    }

    public function postUploadsDelete(Upload $upload)
    {
        if (Auth::user()->id != $upload->user_id) {
            flash()->error('That file is not yours, you cannot delete it!');

            return redirect()->back();
        }

        $upload->forceDelete();
        flash()->success($upload->original_name . ' has been deleted.');

        return redirect()->back();
    }

    public function postResetKey()
    {
        do {
            $newKey = str_random(Helpers::API_KEY_LENGTH);
        } while (User::whereApikey($newKey)->first());

        $user = Auth::user();
        $user->fill(['apikey' => $newKey])->save();
        flash()->success('Your API key was reset. New API key: ' . $user->apikey)->important();

        Mail::queue(['text' => 'emails.user.api_key_reset'], $user->toArray(), function (Message $message) use ($user) {
            $message->subject(sprintf("[%s] API Key Reset", config('upste.domain')));
            $message->to($user->email);
        });

        return redirect()->route('account');
    }
}
