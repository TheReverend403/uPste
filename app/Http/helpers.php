<?php

namespace App;

use App\Models\Upload;
use Auth;
use Cache;
use Exception;
use Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Helpers
{
    const PAGINATION_DEFAULT_ITEMS = 9;
    const API_KEY_LENGTH = 64;

    /**
     * Invalidates cache items like user counts, upload counts etc...
     */
    public static function invalidateCache()
    {
        if (Auth::check()) {
            Cache::forget('uploads_count:' . Auth::id());
            Cache::forget('uploads_size:' . Auth::id());
        }

        Cache::forget('users');
        Cache::forget('uploads');
        Cache::forget('uploads_total_size');
    }

    /**
     * Handle sending a file, preferably by offloading to the webserver.
     *
     * @param Upload $upload
     * @return mixed
     */
    public static function sendFile(Upload $upload)
    {
        switch (config('upste.sendfile_method')) {
            case 'x-accel':
                return response()->make()->header('X-Accel-Redirect', '/' . $upload->getPath())
                    ->header('Content-Type', '')
                    ->header('Content-Disposition', sprintf('inline; filename="%s"', $upload->original_name));
                break;
            case 'x-sendfile':
                return response()->make()->header('X-Sendfile', $upload->getPath(true))
                    ->header('Content-Type', '')
                    ->header('Content-Disposition', sprintf('inline; filename="%s"', $upload->original_name));
                break;
            default:
                return response()->download($upload->getPath(true), $upload->original_name);
        }
    }

    // http://stackoverflow.com/questions/2510434/format-bytes-to-kilobytes-megabytes-gigabytes
    /**
     * @param $bytes
     * @param int $precision
     * @return string
     */
    public static function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1000));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1000, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Checks whether or not it's safe to attempt to strip exif tags from a file
     *
     * @param $file
     * @return bool
     */
    public static function shouldStripExif(UploadedFile $file)
    {
        if (!config('upste.strip_exif')) {
            return false;
        }

        if (function_exists('exif_imagetype')) {
            try {
                switch (exif_imagetype($file)) {
                    case IMAGETYPE_JPEG:
                    case IMAGETYPE_PNG:
                        return true;
                        break;
                    default:
                        return false;
                        break;
                }
            } catch (Exception $ex) {
                Log::error($ex->getMessage());

                return false;
            }
        }

        return false;
    }

    public static function shouldThumbnail(UploadedFile $file)
    {
        if (function_exists('exif_imagetype')) {
            try {
                switch (exif_imagetype($file)) {
                    case IMAGETYPE_JPEG:
                    case IMAGETYPE_PNG:
                    case IMAGETYPE_GIF:
                        return true;
                        break;
                    default:
                        return false;
                        break;
                }
            } catch (Exception $ex) {
                Log::error($ex);

                return false;
            }
        }

        return false;
    }

    /**
     * Determines the real file extension of an image via exif_imagetype
     *
     * @param UploadedFile $file
     * @return string
     */
    public static function getImageType(UploadedFile $file)
    {
        if (function_exists('exif_imagetype')) {
            try {
                $imageType = exif_imagetype($file);
                if ($imageType !== false) {
                    return image_type_to_extension($imageType, false);
                }
            } catch (Exception $ex) {
                Log::error($ex);

                return $file->getClientOriginalExtension();
            }
        }

        return $file->getClientOriginalExtension();
    }
}
