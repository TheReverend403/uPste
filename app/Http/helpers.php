<?php

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Helpers
{
    const SUPERUSER_ID = 1;
    const PAGINATION_DEFAULT_ITEMS = 15;
    const NEW_USER_DAYS = 7; // How long is a user considered "new"
    const API_KEY_LENGTH = 64;
    const DB_CACHE_TIME = 5; // Minutes
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
                switch(exif_imagetype($file)) {
                    case IMAGETYPE_JPEG:
                    case IMAGETYPE_PNG:
                        return true;
                        break;
                    default:
                        return false;
                        break;
                }
            } catch (Exception $ex) {
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
    public static function getImageType(UploadedFile $file) {
        if (function_exists('exif_imagetype')) {
            try {
                $imageType = exif_imagetype($file);
                if ($imageType !== false) {
                    return image_type_to_extension($imageType);
                }
            } catch (Exception $ex) {
                return $file->getClientOriginalExtension();
            }
        }
        return $file->getClientOriginalExtension();
    }
}
