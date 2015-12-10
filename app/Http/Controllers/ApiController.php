<?php

namespace App\Http\Controllers;

use App\Upload;
use Auth;

use App\Http\Requests;
use Input;
use Response;
use Storage;

class ApiController extends Controller
{
    public function upload()
    {
        if (!Input::hasFile('file')) {
            return response()->json(['upload_file_not_found'], 400);
        }

        $file = Input::file('file');
        if (!$file->isValid()) {
            return response()->json(['invalid_file_upload'], 400);
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
            'hash' => sha1_file($file),
            'name' => $newName,
            'size' => $file->getSize(),
            'original_name' => $file->getClientOriginalName()
        ]);

        $upload->save();

        Storage::disk()->put('uploads/'.$newName,
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
