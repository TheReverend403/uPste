<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Upload;
use App\Models\User;
use Auth;
use Cache;
use App\Helpers;
use Illuminate\Mail\Message;
use Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AccountController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $userUploadCount = Cache::rememberForever('uploads_count:' . Auth::id(), function () {
            return Auth::user()->uploads->count();
        });

        $userUploadTotalSize = Cache::rememberForever('uploads_size:' . Auth::id(), function () {
            return Auth::user()->uploads->sum('size');
        });

        $userStorageQuota = Helpers::formatBytes(Cache::get('uploads_size:' . Auth::id()));
        if (config('upste.user_storage_quota') > 0 && !Auth::user()->isPrivilegedUser()) {
            $userStorageQuota = sprintf("%s / %s", $userStorageQuota, Helpers::formatBytes(config('upste.user_storage_quota')));
        }

        view()->share(compact('userUploadCount', 'userUploadTotalSize', 'userStorageQuota'));
    }

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

    public function getUploads()
    {
        $uploads = Auth::user()->uploads()->orderBy('created_at', 'desc')->paginate(Helpers::PAGINATION_DEFAULT_ITEMS);

        return view('account.uploads', compact('uploads'));
    }

    public function postUploadsDelete(Upload $upload)
    {
        if (Auth::id() !== $upload->user_id && !Auth::user()->isPrivilegedUser()) {
            throw new NotFoundHttpException;
        }

        $upload->forceDelete();
        flash()->success(trans('messages.file_deleted', ['name' => $upload->original_name]));

        return redirect()->back();
    }

    public function postResetKey()
    {
        do {
            $newKey = str_random(Helpers::API_KEY_LENGTH);
        } while (User::whereApikey($newKey)->first());

        $user = Auth::user();
        $user->fill(['apikey' => $newKey])->save();
        flash()->success(trans('messages.api_key_changed', ['api_key' => $newKey]))->important();

        Mail::queue(['text' => 'emails.user.api_key_reset'], $user->toArray(), function (Message $message) use ($user) {
            $message->subject(sprintf("[%s] API Key Reset", config('upste.domain')));
            $message->to($user->email);
        });

        return redirect()->route('account');
    }

    public function getThumbnail(Upload $upload)
    {
        if (Auth::id() !== $upload->user_id && !Auth::user()->isPrivilegedUser()) {
            throw new NotFoundHttpException;
        }

        return response()->download(storage_path('app/thumbnails/' . $upload->name));
    }
}
