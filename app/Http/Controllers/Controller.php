<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Session;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    public function getNotAllowed() {
        Session::flash('warning', 'That URL is for POST requests only.');
        return redirect()->route('account');
    }
}
