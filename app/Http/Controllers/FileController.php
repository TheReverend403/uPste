<?php

namespace App\Http\Controllers;

use App\Models\Upload;

use App\Http\Requests;
use Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Teapot\StatusCode;

class FileController extends Controller
{
    public function index(Upload $upload)
    {
        if (Storage::exists('uploads/' . $upload->name)) {
            response()->make('', StatusCode::OK, ['X-SendFile' => Storage::getDriver()->getAdapter()->getPathPrefix() . '/' . $upload->name]);
        } else {
            throw new NotFoundHttpException();
        }
    }
}
