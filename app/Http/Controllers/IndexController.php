<?php

namespace App\Http\Controllers;

use Auth;
use App\Http\Requests;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        if (Auth::user()) {
            return redirect()->route('account');
        }
        return view('index');
    }
}
