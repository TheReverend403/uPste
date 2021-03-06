<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Mail;

class AdminController extends Controller
{
    public function __construct()
    {
        if (config('upste.require_user_approval')) {
            $requestCount = User::whereEnabled(false)->whereConfirmed(true)->count();
            view()->share('requestCount', $requestCount);
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

    public function getRequests(Request $request)
    {
        $users = User::whereEnabled(false)->whereConfirmed(true)->orderBy('created_at', 'asc')->paginate($request->user()->preferences->pagination_items);

        return view('admin.requests', compact('users'));
    }

    public function getUsers(Request $request)
    {
        $users = User::whereEnabled(true)->whereConfirmed(true)->paginate($request->user()->preferences->pagination_items);
        return view('admin.users', compact('users'));
    }

    public function postUserBan(User $user)
    {
        if ($user->isSuperUser()) {
            flash()->error(trans('messages.admin.failed_superuser_action', ['type' => 'ban']));

            return redirect()->route('admin.users');
        }

        $user->fill(['banned' => true])->save();
        flash()->success(trans('messages.admin.banned_user', ['name' => $user->name]));

        return redirect()->route('admin.users');
    }

    public function postUserDelete(User $user)
    {
        if ($user->isSuperUser()) {
            flash()->error(trans('messages.admin.failed_superuser_action', ['type' => 'delete']));

            return redirect()->route('admin.users');
        }

        $user->forceDelete();
        flash()->success(trans('messages.admin.deleted_user', ['name' => $user->name]));

        return redirect()->route('admin.users');
    }

    public function postUserUnban(User $user)
    {
        $user->fill(['banned' => false])->save();
        flash()->success(trans('messages.admin.unbanned_user', ['name' => $user->name]));

        return redirect()->route('admin.users');
    }

    public function getUploads(Request $request, User $user)
    {
        $allUploads = $user->uploads();
        $uploads = $allUploads->orderBy('created_at', 'desc')->paginate($request->user()->preferences->pagination_items);

        return view('admin.uploads', compact('uploads', 'user'));
    }

    public function postUserAccept(User $user)
    {
        $user->enabled = true;
        $user->save();

        $loginRoute = route('login');
        Mail::queue(['text' => 'emails.user.account_accepted'], compact('user', 'loginRoute'), function (Message $message) use ($user) {
            $message->subject('Account Request Accepted');
            $message->to($user->email);
        });

        flash()->success(trans('messages.admin.account_accepted', ['name' => $user->name]));

        return redirect()->route('admin.users');
    }

    public function postUserReject(User $user)
    {
        Mail::queue(['text' => 'emails.user.account_rejected'], compact('user'), function (Message $message) use ($user) {
            $message->subject('Account Request Rejected');
            $message->to($user->email);
        });

        $user->forceDelete();
        flash()->success(trans('messages.admin.account_rejected', ['name' => $user->name]));

        return redirect()->route('admin.users');
    }
}
