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
use Symfony\Component\HttpFoundation\File\File;
use Teapot\StatusCode;

class UploadController extends Controller
{
    public function post(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json([trans('messages.upload_file_not_found')], StatusCode::BAD_REQUEST);
        }

        $uploadedFile = $request->file('file');
        if (!$uploadedFile->isValid()) {
            return response()->json([trans('messages.invalid_file_upload')], StatusCode::BAD_REQUEST);
        }

        if ($uploadedFile->getSize() >= config('upste.upload_limit')) {
            return response()->json([trans(
                'messages.upload_too_large',
                ['limit' => Helpers::formatBytes(config('upste.upload_limit'))]
            )], StatusCode::REQUEST_ENTITY_TOO_LARGE);
        }

        // If this upload would hit the quota defined in .env, reject it.
        if (config('upste.user_storage_quota') > 0 && !Auth::user()->isPrivilegedUser() &&
            (Cache::get('uploads_size:' . Auth::id()) + $uploadedFile->getSize()) >= config('upste.user_storage_quota')) {
            return response()->json([trans(
                'messages.reached_upload_limit',
                ['limit' => Helpers::formatBytes(config('upste.user_storage_quota'))]
            )], StatusCode::FORBIDDEN);
        }

        $ext = strtolower($uploadedFile->getClientOriginalExtension());
        if (empty($ext)) {
            $ext = 'txt';
        }

        $originalHash = sha1_file($uploadedFile);
        $originalName = $uploadedFile->getClientOriginalName();

        // Check to see if we already have this file for this user.
        $existing = Upload::whereOriginalHash($originalHash)->whereUserId(Auth::id())->first();
        if ($existing) {
            $result = [
                'url'  => route('files.get', $existing)
            ];

            $existing->original_name = $originalName;
            $existing->save();

            return response()->json($result, StatusCode::CREATED, [], JSON_UNESCAPED_SLASHES);
        }

        $randomLen = config('upste.upload_slug_length');
        do {
            $newName = str_random($randomLen++) . ".$ext";
        } while (Upload::whereName($newName)->first() || $newName === 'index.php');

        $upload = new Upload([
            'name'          => $newName,
            'original_name' => $originalName,
            'original_hash' => $originalHash
        ]);

        $uploadFileHandle = fopen($uploadedFile->getRealPath(), 'rb');
        Storage::put($upload->getPath(), $uploadFileHandle);
        fclose($uploadFileHandle);

        if (Helpers::shouldThumbnail($uploadedFile)) {
            try {
                $img = Image::make($uploadedFile);
            } catch (NotReadableException $ex) {
                Log::error($ex);
                return response()->json([trans('messages.could_not_read_image')], StatusCode::INTERNAL_SERVER_ERROR);
            } catch (NotSupportedException $ex) {
                Log::error($ex);
                return response()->json([trans('messages.unsupported_image_type')], StatusCode::INTERNAL_SERVER_ERROR);
            }

            try {
                $upload->createDirs();
                $img->backup();
                $img->resize(128, 128)->save($upload->getThumbnailPath(true));
                $img->reset();
            } catch (NotWritableException $ex) {
                Log::error($ex);
                $upload->deleteDirs();
                return response()->json([trans('messages.could_not_write_image')], StatusCode::INTERNAL_SERVER_ERROR);
            }

            if (Helpers::shouldStripExif($uploadedFile)) {
                try {
                    $img->save($upload->getPath(true));
                } catch (NotWritableException $ex) {
                    Log::error($ex);
                    $upload->deleteDirs();
                    return response()->json([trans('messages.could_not_write_image')], StatusCode::INTERNAL_SERVER_ERROR);
                }
            }
            $img->destroy();
        }

        $savedFile = new File($upload->getPath(true));
        $upload->hash = sha1_file($savedFile);
        $upload->size = $savedFile->getSize();

        Auth::user()->uploads()->save($upload);

        $result = [
            'url'  => route('files.get', $upload)
        ];

        return response()->json($result, StatusCode::CREATED, [], JSON_UNESCAPED_SLASHES);
    }

    public function get(Request $request)
    {
        $user = Auth::user();

        if (Cache::get('uploads_count:' . $user->id) !== 0) {
            $uploads = $user->uploads->slice(0, $request->input('limit', Helpers::PAGINATION_DEFAULT_ITEMS));
            return response()->json($uploads, StatusCode::CREATED, [], JSON_UNESCAPED_SLASHES);
        }
        return response()->json([trans('messages.no_uploads_found')], StatusCode::NOT_FOUND, [], JSON_UNESCAPED_SLASHES);
    }
}
