<?php

namespace App\Http\Controllers;

use Cache;
use DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Session;
use View;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    protected $site_stats;

    public function __construct()
    {
        $cache_time = 5; // Minutes
        $this->site_stats['users'] = Cache::remember('users', $cache_time, function () {
            return DB::table('users')->where('enabled', true)->count();
        });

        $this->site_stats['uploads'] = Cache::remember('uploads', $cache_time, function () {
            return DB::table('uploads')->count();
        });

        View::share('site_stats', $this->site_stats);
    }

    public function getNotAllowed()
    {
        flash()->warning('That URL is for POST requests only.');
        return redirect()->route('account');
    }
}
