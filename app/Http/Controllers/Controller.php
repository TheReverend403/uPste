<?php

namespace App\Http\Controllers;

use Cache;
use DB;
use Helpers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use View;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $userCount = Cache::remember('users', Helpers::DB_CACHE_TIME, function () {
            return DB::table('users')->where('enabled', true)->count();
        });

        $uploadCount = Cache::remember('uploads', Helpers::DB_CACHE_TIME, function () {
            return DB::table('uploads')->count();
        });

        View::share(compact('userCount', 'uploadCount'));
    }
}
