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

    protected $site_stats;

    public function __construct()
    {
        $cache_time = 5; // Minutes
        $user_count = Cache::remember('users', $cache_time, function () {
            return DB::table('users')->where('enabled', true)->count();
        });

        $upload_count = Cache::remember('uploads', $cache_time, function () {
            return DB::table('uploads')->count();
        });

        View::share(compact('user_count', 'upload_count'));
    }
}
