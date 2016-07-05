<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Models\Upload;
use App\Http\Requests;
use Cache;
use DB;
use Illuminate\Http\Request;
use Log;
use Storage;
use Teapot\StatusCode;

class DownloadController extends Controller
{
    public function get(Request $request, Upload $upload)
    {
        if (Storage::exists($upload->getPath())) {
            if ($upload->user->banned) {
                Log::info('Refusing to serve file for banned user.', ['user' => $upload->user->name, 'file' => $upload->name]);
                return abort(StatusCode::NOT_FOUND);
            }

            if (!$request->user() || $request->user()->id !== $upload->user_id) {
                $cacheKey = sprintf('cached_view:%s:%s', $request->ip(), $upload->hash);
                if (!Cache::has($cacheKey)) {
                    Cache::put($cacheKey, 1, 60);
                    DB::table('uploads')->where('id', $upload->id)->increment('views');
                }
            }

            return Helpers::sendFile($upload);
        }

        return abort(StatusCode::NOT_FOUND);
    }
}
