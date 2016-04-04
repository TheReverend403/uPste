<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Upload;
use Auth;
use Cache;
use App\Helpers;
use Illuminate\Http\Request;
use Image;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Exception\NotSupportedException;
use Intervention\Image\Exception\NotWritableException;
use Log;
use Storage;
use Teapot\StatusCode;

class UploadController extends Controller
{
    public function post(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json([trans('messages.upload_file_not_found')], StatusCode::BAD_REQUEST);
        }

        $file = $request->file('file');
        if (!$file->isValid()) {
            return response()->json([trans('messages.invalid_file_upload')], StatusCode::BAD_REQUEST);
        }

        if ($file->getSize() >= config('upste.upload_limit')) {
            return response()->json([trans(
                'messages.upload_too_large',
                ['limit' => Helpers::formatBytes(config('upste.upload_limit'))]
            )], StatusCode::REQUEST_ENTITY_TOO_LARGE);
        }

        // If this upload would hit the quota defined in .env, reject it.
        if (config('upste.user_storage_quota') > 0 && !Auth::user()->isPrivilegedUser() &&
            (Cache::get('uploads_size:' . Auth::id()) + $file->getSize()) >= config('upste.user_storage_quota')) {
            return response()->json([trans(
                'messages.reached_upload_limit',
                ['limit' => Helpers::formatBytes(config('upste.user_storage_quota'))]
            )], StatusCode::FORBIDDEN);
        }

        $ext = $file->getClientOriginalExtension();
        if (empty($ext)) {
            $ext = 'txt';
        }

        $originalHash = sha1_file($file);
        $originalName = $file->getClientOriginalName();

        // Check to see if we already have this file for this user.
        $existing = Upload::whereOriginalHash($originalHash)->whereUserId(Auth::id())->first();
        if ($existing) {
            $result = [
                'url'  => route('files.get', $existing)
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
            'user_id'       => Auth::id(),
            'hash'          => sha1_file($file),
            'name'          => $newName,
            'size'          => $file->getSize(),
            'original_name' => $originalName,
            'original_hash' => $originalHash
        ]);
        $upload->save();
        
        $uploadFileHandle = fopen($file->getRealPath(), 'rb');
        Storage::put("uploads/$newName", $uploadFileHandle);

        $result = [
            'url'  => route('files.get', $upload)
        ];

        return response()->json($result, StatusCode::CREATED, [], JSON_UNESCAPED_SLASHES);
    }

    public function get(Request $request)
    {
        $user = Auth::user();

        if (Cache::get('uploads_count:' . $user->id) !== 0) {
            $uploads = $user->uploads->slice(0, $request->input('limit', $user->uploads->count()));
            return response()->json($uploads, StatusCode::CREATED, [], JSON_UNESCAPED_SLASHES);
        }
        return response()->json([trans('messages.no_uploads_found')], StatusCode::NOT_FOUND, [], JSON_UNESCAPED_SLASHES);
    }
}
