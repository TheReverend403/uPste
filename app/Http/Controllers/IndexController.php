<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Auth;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        if (Auth::check()) {
            return redirect()->route('account');
        }

        return view('index');
    }
}
