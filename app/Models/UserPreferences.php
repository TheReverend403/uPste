<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
