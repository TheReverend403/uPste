<?php

namespace App\Models;

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
 */
class Upload extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'hash', 'size', 'user_id', 'original_name'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Get the user that owns the upload.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id', 'user_id');
    }

    public function forceDelete()
    {
        if (Storage::exists("uploads/" . $this->name)) {
            Storage::delete("uploads/" . $this->name);
        }

        return parent::forceDelete();
    }
}
