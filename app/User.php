<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];
    const TABLE_NAME = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = [
//        'account_type',
//        'can_change_username',
//        'username',
//        'name',
//        'email',
//        'password',
//        'gender',
//        'phone',
//        'address',
//        'avatar',
//        'dob',
//        'state',
//        'description',
//        'role_id'
//    ];

    /**
     * Get user date of birth
     *
     * @return null|string
     */
    public function getDob()
    {
        $dob = explode('-', $this->attributes['dob']);
        return $dob[2] . '/' . $dob[1] . '/' . $dob[0];
    }

    /**
     * Get user avatar
     *
     * @return null|string
     */
    public function getAvatar()
    {

    }

    /**
     * Check if user is online
     *
     * @return bool
     */
    public function isOnline()
    {
        return Cache::has('active-user-' . $this->id);
    }

    /**
     * Flush Cache
     */
    public function pullCache()
    {
        Cache::flush();
    }

    public function setCache()
    {
        return true;
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * A user belong to a role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * A user write many log message
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany(Log::class);
    }
}
