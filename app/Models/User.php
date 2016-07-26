<?php

namespace App\Models;

use App\Helpers;
use Cache;
use DB;
use Eloquent;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Storage;

/**
 * App\Models\User
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $apikey
 * @property string $password
 * @property boolean $admin
 * @property boolean $enabled
 * @property boolean $banned
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Upload[] $uploads
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereApikey($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereAdmin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereEnabled($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereBanned($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\UserPreferences $preferences
 * @property boolean $confirmed
 * @property string $confirmation_code
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereConfirmed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereConfirmationCode($value)
 */
class User extends Eloquent implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'apikey', 'password', 'enabled', 'banned', 'admin', 'confirmed', 'confirmation_code'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'apikey', 'enabled', 'banned', 'admin'];

    public static function count()
    {
        return Cache::rememberForever('users', function () {
            return DB::table('users')->where('enabled', true)->count();
        });
    }

    /**
     * Get this user's uploads.
     */
    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }

    public function preferences()
    {
        return $this->hasOne(UserPreferences::class);
    }

    public function forceDelete()
    {
        foreach ($this->uploads as $upload) {
            $upload->forceDelete();
        }

        if (Storage::exists('uploads/' . md5($this->id))) {
            Storage::deleteDirectory('uploads/' . md5($this->id));
        }

        if (Storage::exists('thumbnails/' . md5($this->id))) {
            Storage::deleteDirectory('thumbnails/' . md5($this->id));
        }

        $this->invalidateCache();
        $this->invalidateGlobalCache();

        return parent::forceDelete();
    }

    public function invalidateCache()
    {
        Cache::forget('uploads_size:' . $this->id);
        Cache::forget('uploads_count:' . $this->id);
    }

    private function invalidateGlobalCache() {
        Cache::forget('users');
    }

    public function setEnabledAttribute($value)
    {
        $this->attributes['enabled'] = $value;
        $this->invalidateGlobalCache();
    }

    public function getUploadsCount()
    {
        return Cache::rememberForever('uploads_count:' . $this->id, function () {
            return $this->uploads->count();
        });
    }

    public function getStorageQuota()
    {
        $userStorageQuota = Helpers::formatBytes($this->getUploadsSize());
        if (config('upste.user_storage_quota') > 0 && !$this->isPrivilegedUser()) {
            $userStorageQuota = sprintf("%s / %s", $userStorageQuota, Helpers::formatBytes(config('upste.user_storage_quota')));
        }

        return $userStorageQuota;
    }

    public function getUploadsSize()
    {
        return Cache::rememberForever('uploads_size:' . $this->id, function () {
            return $this->uploads->sum('size');
        });
    }

    public function isPrivilegedUser()
    {
        return $this->admin || $this->isSuperUser();
    }

    public function isSuperUser()
    {
        return $this->id === 1;
    }
}
