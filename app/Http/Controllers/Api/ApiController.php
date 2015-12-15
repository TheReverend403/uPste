<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Upload;
use Auth;
use Input;
use Response;
use Storage;

class ApiController extends Controller
{
    public function postUpload()
    {
        if (!Input::hasFile('file')) {
            return response()->json(['upload_file_not_found'], 400);
        }

        $file = Input::file('file');
        if (!$file->isValid()) {
            return response()->json(['invalid_file_upload'], 400);
        }

        $fileHash = sha1_file($file);
        $original_name = $file->getClientOriginalName();

        // Check to see if we already have this file for this user.
        $existing = Upload::whereHash($fileHash)->whereUserId(Auth::user()->id)->first();
        if ($existing) {
            $result = [
                'code' => 200,
                'hash' => $fileHash,
                'url' => env('UPLOAD_URL') . '/' . $existing->name
            ];

            $existing->original_name = $original_name;
            // Force-update updated_at to move $existing to the top of /u/uploads
            $existing->touch();
            $existing->save();

            $response = Response::make(json_encode($result, JSON_UNESCAPED_SLASHES), 200);
            $response->header('Content-Type', 'application/json');
            return $response;
        }

        $ext = $file->getClientOriginalExtension();
        if (!$ext) {
            $ext = 'txt';
        }

        $randomLen = 4;
        do {
            $newName = str_random($randomLen++) . ".$ext";
        } while (Storage::disk()->exists("uploads/$newName"));

        $upload = Upload::create([
            'user_id' => Auth::user()->id,
            'hash' => $fileHash,
            'name' => $newName,
            'size' => $file->getSize(),
            'original_name' => $original_name
        ]);

        $upload->save();

        Storage::disk()->put("uploads/$newName",
            file_get_contents($file->getRealPath())
        );

        $result = [
            'code' => 200,
            'hash' => $upload->getAttribute('hash'),
            'url' => env('UPLOAD_URL') . '/' . $newName
        ];

        $response = Response::make(json_encode($result, JSON_UNESCAPED_SLASHES), 200);
        $response->header('Content-Type', 'application/json');
        return $response;
    }
}
