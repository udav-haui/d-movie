<?php

namespace App;

use App\Helper\Data;
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
        if (!$this->attributes['dob']) {
            return '';
        }
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
        return $this->avatar ? Data::STORAGE . $this->avatar : '/images/icons/account.png';
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
     * Check if is admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->account_type == Data::IS_ADMIN;
    }

    /**
     * User has permission
     *
     * @param string $key
     * @return bool
     */
    public function canAccess($key)
    {
        $role = $this->role;
        if ($role) {
            $permission = $role->permissions->where('permission_code', $key)->first();
            if ($permission) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user can change username
     *
     * @return bool
     */
    public function canChangePassword()
    {
        return $this->can_change_username == 1;
    }

    /**
     * Check if user is login with social account
     *
     * @return bool
     */
    public function loginWithSocialAcc()
    {
        return $this->login_with_social_account == 1;
    }

    /**
     * Check if user is active
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->state === 1;
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
