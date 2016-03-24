<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Upload;
use Auth;
use Cache;
use App\Helpers;
use Image;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Exception\NotSupportedException;
use Intervention\Image\Exception\NotWritableException;
use Log;
use Request;
use Storage;
use Teapot\StatusCode;

class UploadController extends Controller
{
    public function post()
    {
        if (!Request::hasFile('file')) {
            return response()->json([trans('messages.upload_file_not_found')], StatusCode::BAD_REQUEST);
        }

        $file = Request::file('file');
        if (!$file->isValid()) {
            return response()->json([trans('messages.invalid_file_upload')], StatusCode::BAD_REQUEST);
        }

        if ($file->getSize() >= config('upste.upload_limit')) {
            return response()->json([trans('messages.upload_too_large')], StatusCode::REQUEST_ENTITY_TOO_LARGE);
        }

        // If this upload would hit the quota defined in .env, reject it.
        if (config('upste.user_storage_quota') > 0 && !Auth::user()->admin &&
            (Cache::get('uploads_size:' . Auth::user()->id) + $file->getSize()) >= config('upste.user_storage_quota')) {
            return response()->json([trans('messages.reached_upload_limit')], StatusCode::FORBIDDEN);
        }

        $ext = $file->getClientOriginalExtension();
        if (empty($ext)) {
            $ext = 'txt';
        }

        $originalHash = sha1_file($file);
        $originalName = $file->getClientOriginalName();

        // Check to see if we already have this file for this user.
        $existing = Upload::whereOriginalHash($originalHash)->whereUserId(Auth::user()->id)->first();
        if ($existing) {
            $result = [
                'url'  => config('upste.upload_url') . $existing->name
            ];

            $existing->original_name = $originalName;
            // Force-update updated_at to move $existing to the top of /u/uploads
            $existing->touch();
            $existing->save();

            return response()->json($result, StatusCode::CREATED, [], JSON_UNESCAPED_SLASHES);
        }

        $randomLen = config('upste.upload_slug_length');
        do {
            $newName = str_random($randomLen++) . ".$ext";
        } while (Storage::exists("uploads/$newName"));

        if (Helpers::shouldThumbnail($file)) {
            try {
                $img = Image::make($file);
            } catch (NotReadableException $ex) {
                Log::error($ex);
                return response()->json([trans('messages.could_not_read_image')], StatusCode::INTERNAL_SERVER_ERROR);
            } catch (NotSupportedException $ex) {
                Log::error($ex);
                return response()->json([trans('messages.unsupported_image_type')], StatusCode::INTERNAL_SERVER_ERROR);
            }

            $img->backup();
            $img->resize(128, 128)->save(storage_path('app/thumbnails/' . $newName));
            $img->reset();

            if (Helpers::shouldStripExif($file)) {
                try {
                    $img->save($file, 100);
                } catch (NotWritableException $ex) {
                    Log::error($ex);
                    return response()->json([trans('messages.could_not_write_image')], StatusCode::INTERNAL_SERVER_ERROR);
                }
            }
            $img->destroy();
        }

        $upload = Upload::create([
            'user_id'       => Auth::user()->id,
            'hash'          => sha1_file($file),
            'name'          => $newName,
            'size'          => $file->getSize(),
            'original_name' => $originalName,
            'original_hash' => $originalHash
        ]);

        $upload->save();
        Storage::put(
            "uploads/$newName",
            file_get_contents($file)
        );

        $result = [
            'url'  => config('upste.upload_url') . $newName
        ];

        return response()->json($result, StatusCode::CREATED, [], JSON_UNESCAPED_SLASHES);
    }

    public function get()
    {
        $user = Auth::user();

        if (Cache::get('uploads_count:' . $user->id) !== 0) {
            $uploads = $user->uploads->slice(0, Request::input('limit', $user->uploads->count()));
            return response()->json($uploads, StatusCode::CREATED, [], JSON_UNESCAPED_SLASHES);
        }
        return response()->json([trans('messages.no_uploads_found')], StatusCode::NOT_FOUND, [], JSON_UNESCAPED_SLASHES);
    }
}
