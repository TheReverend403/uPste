<?php

namespace App\Http\Controllers\Account;

use App\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Mail;
use Teapot\StatusCode;

class AccountController extends Controller
{
    public function getIndex()
    {
        return view('account.index');
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

    public function getUploads(Request $request)
    {
        $uploads = $request->user()->uploads()->orderBy('created_at', 'desc')->paginate($request->user()->preferences->pagination_items);

        return view('account.uploads', compact('uploads'));
    }

    public function deleteUpload(Request $request, Upload $upload)
    {
        if ($request->user()->id !== $upload->user_id && !$request->user()->isPrivilegedUser()) {
            return abort(StatusCode::NOT_FOUND);
        }

        $upload->forceDelete();
        flash()->success(trans('messages.file_deleted', ['name' => $upload->original_name]));

        return redirect()->back();
    }

    public function postResetKey(Request $request)
    {
        do {
            $newKey = str_random(Helpers::API_KEY_LENGTH);
        } while (User::whereApikey($newKey)->first());

        $user = $request->user();
        $user->fill(['apikey' => $newKey])->save();
        flash()->success(trans('messages.api_key_changed', ['api_key' => $newKey]))->important();

        $passwordRoute = route('account.password.email');
        Mail::queue(['text' => 'emails.user.api_key_reset'], compact('user', 'passwordRoute'), function (Message $message) use ($user) {
            $message->subject('API Key Reset');
            $message->to($user->email);
        });

        return redirect()->route('account');
    }

    public function getThumbnail(Request $request, Upload $upload)
    {
        if (!$request->user() || $request->user()->id !== $upload->user_id && !$request->user()->isPrivilegedUser()) {
            return abort(StatusCode::NOT_FOUND);
        }

        return response()->download($upload->getThumbnailPath(true), 'thumbnail.' . $upload->name);
    }
}
