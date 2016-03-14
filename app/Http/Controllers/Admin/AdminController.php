<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Upload;
use App\Models\User;
use Cache;
use Helpers;
use Illuminate\Mail\Message;
use Mail;
use Storage;
use View;

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $requestCount = User::whereEnabled(false)->count();
        View::share('requestCount', $requestCount);
    }

    public function getIndex()
    {
        return redirect()->route('admin.requests');
    }

    public function getRequests()
    {
        $users = User::whereEnabled(false)->orderBy('created_at', 'asc')->paginate(Helpers::PAGINATION_DEFAULT_ITEMS);

        return view('admin.requests', compact('users'));
    }

    public function getUsers()
    {
        $users = User::whereEnabled(true)->with('uploads')->paginate(Helpers::PAGINATION_DEFAULT_ITEMS);

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
        if ($user->id == Helpers::SUPERUSER_ID) {
            flash()->error('You cannot ban the superuser account.');

            return redirect()->back();
        }
        $user->fill(['banned' => true])->save();
        flash()->success('Banned user ' . $user->name);

        return redirect()->back();
    }

    public function postUserDelete(User $user)
    {
        if ($user->id == Helpers::SUPERUSER_ID) {
            flash()->error('You cannot delete the superuser account.');

            return redirect()->back();
        }

        // Reimplemented here to allow CASCADE to do it's job, as opposed to using $upload->forceDelete()
        foreach ($user->uploads as $upload) {
            if (Storage::exists("uploads/" . $upload->name)) {
                Storage::delete("uploads/" . $upload->name);
            }
        }

        $user->forceDelete();
        flash()->success('Deleted user ' . $user->name);

        return redirect()->back();
    }

    public function postUserUnban(User $user)
    {
        $user->fill(['banned' => false])->save();
        flash()->success('Unbanned user ' . $user->name);

        return redirect()->back();
    }

    public function getUploads(User $user)
    {
        $uploads = $user->uploads()->orderBy('created_at', 'desc')->paginate(Helpers::PAGINATION_DEFAULT_ITEMS);

        return view('admin.uploads', compact('uploads', 'user'));
    }

    public function postUploadsDelete(Upload $upload)
    {
        $upload->forceDelete();
        flash()->success($upload->name . ' has been deleted.');

        return redirect()->back();
    }

    public function postUserAccept(User $user)
    {
        $user->fill(['enabled' => true])->save();

        Mail::queue(['text' => 'emails.user.account_accepted'], $user->toArray(), function (Message $message) use ($user) {
            $message->subject(sprintf("[%s] Account Request Accepted", config('upste.domain')));
            $message->to($user->email);
        });

        flash()->success('Approved user ' . $user->name);
        Helpers::invalidateCache();

        return redirect()->back();
    }

    public function postUserReject(User $user)
    {
        Mail::queue(['text' => 'emails.user.account_rejected'], $user->toArray(), function (Message $message) use ($user) {
            $message->subject(sprintf("[%s] Account Request Rejected", config('upste.domain')));
            $message->to($user->email);
        });

        $user->forceDelete();
        flash()->success('Rejected user ' . $user->name);

        return redirect()->back();
    }
}
