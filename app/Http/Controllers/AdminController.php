<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Upload;
use App\User;
use Illuminate\Mail\Message;
use Mail;
use Storage;
use View;

class AdminController extends Controller
{
    protected $request_count;

    public function __construct()
    {
        parent::__construct();
        $this->request_count = User::whereEnabled(false)->count();
        View::share('request_count', $this->request_count);
    }

    public function getIndex()
    {
        return redirect()->route('admin.requests');
    }

    public function getRequests()
    {
        $users = User::whereEnabled(false)->orderBy('created_at', 'asc')->paginate(15);
        return view('admin.requests', compact('users'));
    }

    public function getUsers()
    {
        $users = User::whereEnabled(true)->paginate(15);
        return view('admin.users', ['users' => $users]);
    }

    public function postUserBan(User $user)
    {
        if ($user->id == 1) {
            flash()->error('You cannot ban the superuser account.');
            return redirect()->back();
        }
        $user->fill(['banned' => true])->save();
        flash()->success('Banned user ' . $user->name);
        return redirect()->back();
    }

    public function postUserDelete(User $user)
    {
        if ($user->id == 1) {
            flash()->error('You cannot delete the superuser account.');
            return redirect()->back();
        }
        foreach ($user->uploads as $upload) {
            if (Storage::disk()->exists("uploads/" . $upload->name)) {
                Storage::disk()->delete("uploads/" . $upload->name);
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
        $uploads = Upload::whereUserId($user->id)->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.uploads', compact('uploads', 'user'));
    }

    public function postUploadsDelete(Upload $upload)
    {
        if (Storage::disk()->exists("uploads/" . $upload->name)) {
            Storage::disk()->delete("uploads/" . $upload->name);
        }
        $upload->forceDelete();
        flash()->success($upload->name . ' has been deleted.');
        return redirect()->back();
    }

    public function postUserAccept(User $user)
    {
        $user->fill(['enabled' => true])->save();
        Mail::queue(['text' => 'emails.user.account_accepted'], ['user' => $user], function (Message $message) use ($user) {
            $message->subject(sprintf("[%s] Account Request Accepted", env('DOMAIN')));
            $message->to($user->email);
        });
        flash()->success('Approved user ' . $user->name);
        return redirect()->back();
    }

    public function postUserReject(User $user)
    {
        Mail::queue(['text' => 'emails.user.account_rejected'], ['user' => $user], function (Message $message) use ($user) {
            $message->subject(sprintf("[%s] Account Request Rejected", env('DOMAIN')));
            $message->to($user->email);
        });
        $user->forceDelete();
        flash()->success('Rejected user ' . $user->name);
        return redirect()->back();
    }
}
