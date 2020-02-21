<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SocialAccount
 *
 * @package App
 */
class SocialAccount extends Model
{
    /**
     * Fillable array
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'provider_user_id',
        'provider'
    ];

    /**
     * Get socciate user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * A user has a facebook account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
