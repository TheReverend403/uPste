<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Upload;
use App\User;
use Mail;
use Session;
use Storage;

class AdminController extends Controller
{
    public function getIndex()
    {
        return redirect()->route('admin.requests');
    }

    public function getRequests()
    {
        $users = User::where('enabled', false)->orderBy('created_at', 'asc')->paginate(15);
        return view('admin.requests', compact('users'));
    }

    public function getUsers()
    {
        $users = User::where('enabled', true)->paginate(15);
        return view('admin.users', ['users' => $users]);
    }

    public function postUserBan($user)
    {
        if ($user->id == 1) {
            Session::flash('alert', 'You cannot ban the superuser account.');
            return redirect()->back();
        }
        $user->fill(['banned' => true])->save();
        Session::flash('info', 'Banned user ' . $user->name);
        return redirect()->back();
    }

    public function postUserDelete($user)
    {
        if ($user->id == 1) {
            Session::flash('alert', 'You cannot delete the superuser account.');
            return redirect()->back();
        }
        foreach ($user->uploads as $upload) {
            if (Storage::disk()->exists("uploads/" . $upload->name)) {
                Storage::disk()->delete("uploads/" . $upload->name);
            }
        }
        $user->forceDelete();
        Session::flash('info', 'Deleted user ' . $user->name);
        return redirect()->back();
    }

    public function postUserUnban($user)
    {
        $user->fill(['banned' => false])->save();
        Session::flash('info', 'Unbanned user ' . $user->name);
        return redirect()->back();
    }

    public function getUploads($user)
    {
        $uploads = Upload::where('user_id', $user->id)->orderBy('updated_at', 'desc')->paginate(15);
        return view('admin.uploads', compact('uploads', 'user'));
    }

    public function postUploadsDelete($upload)
    {
        if (Storage::disk()->exists("uploads/" . $upload->name)) {
            Storage::disk()->delete("uploads/" . $upload->name);
        }
        $upload->forceDelete();
        Session::flash('info', $upload->name . ' has been deleted.');
        return redirect()->back();
    }

    public function postUserAccept($user)
    {
        $user->fill(['enabled' => true])->save();
        Mail::queue(['text' => 'emails.user.account_accepted'], ['user' => $user], function ($message) use ($user) {
            $message->subject(sprintf("[%s] Account Request Accepted", env('DOMAIN')));
            $message->to($user->email);
        });
        Session::flash('info', 'Approved user ' . $user->name);
        return redirect()->back();
    }

    public function postUserReject($user)
    {
        Mail::queue(['text' => 'emails.user.account_rejected'], ['user' => $user], function ($message) use ($user) {
            $message->subject(sprintf("[%s] Account Request Rejected", env('DOMAIN')));
            $message->to($user->email);
        });
        $user->forceDelete();
        Session::flash('info', 'Rejected user ' . $user->name);
        return redirect()->back();
    }
}
