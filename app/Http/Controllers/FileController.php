<?php

namespace App\Http\Controllers;

use App\Models\Upload;

use App\Http\Requests;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Teapot\StatusCode;

class FileController extends Controller
{
    public function index(Upload $upload) {
        $filePath = storage_path('uploads/') . $upload->original_name;
        if (file_exists($filePath)) {
            response()->make('', StatusCode::OK, ['X-SendFile' => $filePath]);
        } else {
            throw new NotFoundHttpException();
        }
    }
}
