<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\User;
use Auth;
use Cache;
use App\Helpers;
use Illuminate\Mail\Message;
use Mail;
use Storage;
use View;

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (config('upste.require_user_approval')) {
            $requestCount = User::whereEnabled(false)->count();
            View::share('requestCount', $requestCount);
        }
    }

    public function getIndex()
    {
        if (config('upste.require_user_approval')) {
            return redirect()->route('admin.requests');
        } else {
            return redirect()->route('admin.users');
        }
    }

    public function getRequests()
    {
        $users = User::whereEnabled(false)->orderBy('created_at', 'asc')->paginate(Auth::user()->preferences->pagination_items);

        return view('admin.requests', compact('users'));
    }

    public function getUsers()
    {
        $users = User::whereEnabled(true)->with('uploads')->paginate(Auth::user()->preferences->pagination_items);

        foreach ($users as $user) {
            Cache::rememberForever('uploads_count:' . $user->id, function () use ($user) {
                return $user->uploads->count();
            });

            Cache::rememberForever('uploads_size:' . $user->id, function () use ($user) {
                return $user->uploads->sum('size');
            });
        }

        return view('admin.users', compact('users'));
    }

    public function postUserBan(User $user)
    {
        if ($user->isSuperUser()) {
            flash()->error(trans('messages.admin.failed_superuser_action', ['type' => 'ban']));

            return redirect()->back();
        }

        $user->fill(['banned' => true])->save();
        flash()->success(trans('messages.admin.banned_user', ['name' => $user->name]));

        return redirect()->back();
    }

    public function postUserDelete(User $user)
    {
        if ($user->isSuperUser()) {
            flash()->error(trans('messages.admin.failed_superuser_action', ['type' => 'delete']));

            return redirect()->back();
        }

        // Reimplemented here to allow CASCADE to do it's job, as opposed to using $upload->forceDelete()
        foreach ($user->uploads as $upload) {
            if (Storage::exists("uploads/" . $upload->name)) {
                Storage::delete("uploads/" . $upload->name);
            }

            if (Storage::exists("thumbnails/" . $upload->name)) {
                Storage::delete("thumbnails/" . $upload->name);
            }
        }

        $user->forceDelete();
        flash()->success(trans('messages.admin.deleted_user', ['name' => $user->name]));

        return redirect()->back();
    }

    public function postUserUnban(User $user)
    {
        $user->fill(['banned' => false])->save();
        flash()->success(trans('messages.admin.unbanned_user', ['name' => $user->name]));

        return redirect()->back();
    }

    public function getUploads(User $user)
    {
        $allUploads = $user->uploads();
        $uploads = $allUploads->orderBy('created_at', 'desc')->paginate(Auth::user()->preferences->pagination_items);
        $uploadsTotalCount = Cache::rememberForever('uploads_count:' . $user->id, function () use ($allUploads) {
            return $allUploads->count();
        });

        $uploadsTotalSize = Helpers::formatBytes(Cache::rememberForever('uploads_size:' . $user->id, function () use ($allUploads) {
            return $allUploads->sum('size');
        }));

        return view('admin.uploads', compact('uploads', 'user', 'uploadsTotalCount', 'uploadsTotalSize'));
    }

    public function postUserAccept(User $user)
    {
        $user->fill(['enabled' => true])->save();

        Mail::queue(['text' => 'emails.user.account_accepted'], $user->toArray(), function (Message $message) use ($user) {
            $message->subject(sprintf("[%s] Account Request Accepted", config('upste.site_name')));
            $message->to($user->email);
        });

        flash()->success(trans('messages.admin.account_accepted', ['name' => $user->name]));
        Helpers::invalidateCache();

        return redirect()->back();
    }

    public function postUserReject(User $user)
    {
        Mail::queue(['text' => 'emails.user.account_rejected'], $user->toArray(), function (Message $message) use ($user) {
            $message->subject(sprintf("[%s] Account Request Rejected", config('upste.site_name')));
            $message->to($user->email);
        });

        $user->forceDelete();
        flash()->success(trans('messages.admin.account_rejected', ['name' => $user->name]));

        return redirect()->back();
    }
}
