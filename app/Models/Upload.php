<?php

namespace App\Models;

use App\Helpers;
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
 */
class Upload extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'hash', 'size', 'user_id', 'original_name', 'original_hash', 'downloads'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['user_id', 'id'];

    /**
     * Get the user that owns the upload.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id', 'user_id');
    }

    public static function create(array $attributes = [])
    {
        Helpers::invalidateCache();

        return parent::create($attributes);
    }

    public function forceDelete()
    {
        if (Storage::exists("uploads/" . $this->name)) {
            Storage::delete("uploads/" . $this->name);
        }

        $file = 'thumbnails/' . $this->name;
        if (Storage::exists($file)) {
            Storage::delete($file);
        }

        Helpers::invalidateCache();

        return parent::forceDelete();
    }

    public function getThumbnail()
    {
        if (Storage::exists('thumbnails/' . $this->name)) {
            return route('account.uploads.thumbnail', ['name' => $this->name]);
        }
        return elixir('assets/img/thumbnail.png');
    }

    public function getRouteKeyName()
    {
        return 'name';
    }
}
