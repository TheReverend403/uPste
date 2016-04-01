<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use App\Http\Requests;
use Auth;
use Log;
use Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Teapot\StatusCode;

class DownloadController extends Controller
{
    public function get(Upload $upload)
    {
        if (Storage::exists('uploads/' . $upload->name)) {
            if ($upload->user->banned) {
                Log::info('Refusing to serve file for banned user.', ['user' => $upload->user->name, 'file' => $upload->name]);
                return abort(StatusCode::NOT_FOUND);
            }

            if (!Auth::check() || Auth::id() !== $upload->user_id) {
                $upload->views = $upload->views + 1;
                $upload->save();
            }

            return response()->make()->header('X-Accel-Redirect', '/uploads/' . $upload->name)->header('Content-Type', '');
        }

        return abort(StatusCode::NOT_FOUND);
    }
}