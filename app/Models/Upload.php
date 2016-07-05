<?php

namespace App\Models;

use Cache;
use DB;
use Illuminate\Database\Eloquent\Model;
use Storage;

/**
 * App\Models\Upload
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $hash
 * @property string $name
 * @property integer $size
 * @property string $original_name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Upload whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Upload whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Upload whereHash($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Upload whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Upload whereSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Upload whereOriginalName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Upload whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Upload whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $original_hash
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Upload whereOriginalHash($value)
 * @property integer $views
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Upload whereViews($value)
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Upload whereDeletedAt($value)
 */
class Upload extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'hash', 'size', 'user_id', 'original_name', 'original_hash', 'downloads', 'views'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['user_id', 'id', 'updated_at'];

    public static function size()
    {
        return Cache::rememberForever('uploads_total_size', function () {
            return DB::table('uploads')->sum('size');
        });
    }

    public static function count()
    {
        return Cache::rememberForever('uploads', function () {
            return DB::table('uploads')->count();
        });
    }

    public function save(array $options = [])
    {
        static::invalidateCache();
        $this->user->invalidateCache();

        return parent::save($options);
    }

    public static function invalidateCache()
    {
        Cache::forget('uploads');
        Cache::forget('uploads_total_size');
    }

    /**
     * Get the user that owns the upload.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function forceDelete()
    {
        $this->deleteDirs();
        $this->invalidateCache();
        $this->user->invalidateCache();

        return parent::forceDelete();
    }

    public function deleteDirs()
    {
        $path = $this->getPath();
        $dir = $this->getDir();
        if (Storage::exists($path)) {
            Storage::delete($path);
            if (!count(Storage::files($dir))) {
                Storage::deleteDir($dir);
            }
        }

        $path = $this->getThumbnailPath();
        $dir = $this->getThumbnailDir();
        if (Storage::exists($path)) {
            Storage::delete($path);
            if (!count(Storage::files($dir))) {
                Storage::deleteDir($dir);
            }
        }
    }

    public function getPath($fullPath = false)
    {
        return $this->getDir($fullPath) . $this->name;
    }

    public function getDir($fullDir = false)
    {
        if ($fullDir) {
            return storage_path(sprintf('app/uploads/%s/%s/', md5($this->user_id), md5(mb_substr($this->name, 0, 1, 'utf-8'))));
        }

        return sprintf('uploads/%s/%s/', md5($this->user_id), md5(mb_substr($this->name, 0, 1, 'utf-8')));
    }

    public function getThumbnailPath($fullPath = false)
    {
        return $this->getThumbnailDir($fullPath) . $this->name;
    }

    public function getThumbnailDir($fullDir = false)
    {
        if ($fullDir) {
            return storage_path(sprintf('app/thumbnails/%s/%s/', md5($this->user_id), md5(mb_substr($this->name, 0, 1, 'utf-8'))));
        }

        return sprintf('thumbnails/%s/%s/', md5($this->user_id), md5(mb_substr($this->name, 0, 1, 'utf-8')));
    }

    /**
     * Migrates files from the old uploads/$filename structure to a more efficient one.
     * Should only be called once from the command line.
     */
    public function migrate()
    {
        if (php_sapi_name() !== 'cli') {
            trigger_error('CLI-only (Upload#migrate()) function called outside of CLI SAPI.', E_USER_ERROR);

            return;
        }

        $this->createDirs();

        if (Storage::exists('uploads/' . $this->name)) {
            Storage::move('uploads/' . $this->name, $this->getPath());
        }

        if (Storage::exists('thumbnails/' . $this->name)) {
            Storage::move('thumbnails/' . $this->name, $this->getThumbnailPath());
        }
    }

    public function createDirs()
    {
        if (!Storage::exists($this->getDir())) {
            Storage::makeDirectory($this->getDir());
        }

        if (!Storage::exists($this->getThumbnailDir())) {
            Storage::makeDirectory($this->getThumbnailDir());
        }
    }

    public function hasPreview()
    {
        return $this->getThumbnailUrl() !== elixir('assets/img/thumbnail.png');
    }

    public function getThumbnailUrl()
    {
        if (Storage::exists($this->getThumbnailDir() . $this->name)) {
            return route('account.uploads.thumbnail', $this);
        }

        return elixir('assets/img/thumbnail.png');
    }

    public function getRouteKeyName()
    {
        return 'name';
    }
}
