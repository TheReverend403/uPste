<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Upload
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $hash
 * @property string $name
 * @property integer $size
 * @property string $original_name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Upload whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Upload whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Upload whereHash($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Upload whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Upload whereSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Upload whereOriginalName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Upload whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Upload whereUpdatedAt($value)
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
        return $this->belongsTo('App\User', 'id', 'user_id');
    }
}
