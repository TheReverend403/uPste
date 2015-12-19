<?php

namespace App\Http\Controllers;

use Cache;
use DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use View;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $cacheTime = 5; // Minutes
        $userCount = Cache::remember('users', $cacheTime, function () {
            return DB::table('users')->where('enabled', true)->count();
        });

        $uploadCount = Cache::remember('uploads', $cacheTime, function () {
            return DB::table('uploads')->count();
        });

        View::share(compact('userCount', 'uploadCount'));
    }
}
