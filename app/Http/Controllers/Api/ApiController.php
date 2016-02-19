<?php

namespace App\Http\Controllers\Api;

use abeautifulsite\SimpleImage;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Upload;
use Auth;
use Exception;
use Helpers;
use Input;
use Storage;
use Teapot\StatusCode;

class ApiController extends Controller
{
    public function postUpload()
    {
        if (!Input::hasFile('file')) {
            return response()->json(['upload_file_not_found'], StatusCode::BAD_REQUEST);
        }

        $file = Input::file('file');
        if (!$file->isValid()) {
            return response()->json(['invalid_file_upload'], StatusCode::BAD_REQUEST);
        }


        $ext = $file->getClientOriginalExtension();
        if (empty($ext)) {
            $ext = 'txt';
        }

        // Strip EXIF tags
        if (Helpers::canHaveExif($file) && in_array($ext, ['png', 'jpg', 'jpeg', 'gif'])) {
            try {
                $img = new SimpleImage($file->getRealPath());
                $img->save($file->getRealPath(), 100, $ext);
            } catch (Exception $e) {
                return response()->json([$e->getMessage()], StatusCode::INTERNAL_SERVER_ERROR);
            }
        }

        $fileHash = sha1_file($file);
        $originalName = $file->getClientOriginalName();

        // Check to see if we already have this file for this user.
        $existing = Upload::whereHash($fileHash)->whereUserId(Auth::user()->id)->first();
        if ($existing) {
            $result = [
                'hash' => $fileHash,
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

        $upload = Upload::create([
            'user_id'       => Auth::user()->id,
            'hash'          => $fileHash,
            'name'          => $newName,
            'size'          => $file->getSize(),
            'original_name' => $originalName
        ]);

        $upload->save();
        Storage::put("uploads/$newName",
            file_get_contents($file->getRealPath())
        );

        $result = [
            'hash' => $upload->getAttribute('hash'),
            'url'  => config('upste.upload_url') . $newName
        ];

        return response()->json($result, StatusCode::CREATED, [], JSON_UNESCAPED_SLASHES);
    }
}
