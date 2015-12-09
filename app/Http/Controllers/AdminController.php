<?php

namespace App\Http\Controllers;

use App\User;

use App\Http\Requests;
use File;
use Mail;
use Session;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function requests()
    {
        $users = User::where('enabled', 0)->get();
        return view('admin.requests', compact('users'));
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', ['users' => $users]);
    }

    public function ban($user)
    {
        if ($user->id == 1) {
            Session::flash('alert',
                'You cannot ban the superuser account.');
            return redirect()->back();
        }
        $user->fill(['banned' => true])->save();
        return redirect()->back();
    }

    public function delete($user)
    {
        if ($user->id == 1) {
            Session::flash('alert',
                'You cannot delete the superuser account.');
            return redirect()->back();
        }
        $path = storage_path() . '/uploads/';
        foreach ($user->uploads as $upload) {
            File::delete($path . $upload->name);
        }
        $user->forceDelete();
        return redirect()->back();
    }

    public function unban($user)
    {
        $user->fill(['banned' => false])->save();
        return redirect()->back();
    }

    public function uploads($user)
    {
        $uploads = $user->uploads;
        return view('admin.uploads', compact('uploads', 'user'));
    }

    public function enable($user)
    {
        $user->fill(['enabled' => true])->save();
        Mail::queue('emails.user.account_approved', ['user' => $user], function($message) use ($user)
        {
            $message->from(env('SITE_EMAIL_FROM'), env('SITE_NAME'));
            $message->subject(sprintf("[%s] Account Approved", env('DOMAIN')));
            $message->to($user->email);
        });
        return redirect()->back();
    }
}
