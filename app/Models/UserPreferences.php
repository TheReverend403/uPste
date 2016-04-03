<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserPreferences
 *
 * @property integer $user_id
 * @property string $timezone
 * @property integer $pagination_items
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserPreferences whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserPreferences whereTimezone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserPreferences wherePaginationItems($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserPreferences whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserPreferences whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserPreferences extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'user_id';
    protected $table = 'preferences';

    protected $fillable = ['user_id', 'timezone', 'pagination_items'];

    /**
     * Get the user that owns the preferences.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
