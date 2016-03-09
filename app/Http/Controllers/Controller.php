<?php

namespace App\Http\Controllers;

use Cache;
use DB;
use Helpers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $userCount = Cache::rememberForever('users', function () {
            return DB::table('users')->where('enabled', true)->count();
        });

        $uploadCount = Cache::rememberForever('uploads', function () {
            return DB::table('uploads')->count();
        });

        $uploadTotalSize = Cache::rememberForever('uploads_total_size', function () {
            return DB::table('uploads')->sum('size');
        });

        view()->share(compact('userCount', 'uploadCount', 'uploadTotalSize'));
    }
}
