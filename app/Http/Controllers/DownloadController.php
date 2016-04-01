<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use App\Http\Requests;
use Auth;
use Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Teapot\StatusCode;

class DownloadController extends Controller
{
    public function get(Upload $upload)
    {
        if (Storage::exists('uploads/' . $upload->name) && !$upload->user->first()->banned) {
            if (!Auth::check() || Auth::id() !== $upload->user_id) {
                $upload->views = $upload->views + 1;
                $upload->save();
            }

            return response()->make()->header('X-Accel-Redirect', '/uploads/' . $upload->name)->header('Content-Type', '');
        }
        return abort(404);
    }
}