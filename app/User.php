<?php

namespace App;

use App\Helper\Data;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];

    /**
     * Define role permission
     */
    const VIEW = 'user-view';
    const CREATE = 'user-create';
    const EDIT = 'user-edit';
    const DELETE = 'user-delete';

    const TABLE_NAME = 'users';

    /**
     * Determine account type
     */
    const ADMIN = 0,
        STAFF = 1,
        CUSTOMER = 2;

    /**
     * Determine account state
     */
    const ACTIVE = 1,
        NOT_VERIFY_BY_ADMIN = 0,
        NOT_ACTIVATE = -1;

    const FIRST_LOGIN_WITH_SOCIAL_ACCOUNT = 1,
        CAN_CHANGE_USERNAME = 1,
        NORMAL_LOGIN = 0;

    /** Other */
    const MALE = 0,
        FEMALE = 1,
        OTHER = 2;
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
        return $this->account_type === self::ADMIN;
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
     * Get user gender
     *
     * @return array|string|null
     */
    public function getGender()
    {
        return $this->getAttribute('gender') === self::MALE ? __('Male') :
            ($this->getAttribute('gender') === self::FEMALE ? __('Female') : __('Other'));
    }

    /**
     * Get name of user
     *
     * @return string
     */
    public function getName()
    {
        return $this->getAttribute('name') ?? __('<i class=\'text-warning\'>Not update</i>');
    }

    /**
     * Check if user can change username
     *
     * @return bool
     */
    public function canChangeUsername()
    {
        return $this->can_change_username == self::CAN_CHANGE_USERNAME;
    }

    /**
     * Get username of user
     *
     * @return array|mixed|string|null
     */
    public function getUserName()
    {
        return $this->username ?? __('<i class=\'text-warning\'>Not update</i>');
    }
    /**
     * Get user status
     *
     * @return array|string|null
     */
    public function getStatus()
    {
        return $this->state === self::ACTIVE ?
            "Active" :
            ($this->state === self::NOT_VERIFY_BY_ADMIN ?
                "Not verify" :
                "Not active");
    }
    /**
     * Get assigned role to user
     *
     * @return string
     */
    public function getRoleName()
    {
        return $this->role->role_name ?? __('No role assigned!');
    }

    /**
     * Check if user is login with social account
     *
     * @return bool
     */
    public function loginWithSocialAcc()
    {
        return $this->login_with_social_account == self::FIRST_LOGIN_WITH_SOCIAL_ACCOUNT;
    }

    /**
     * Check if user is active
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->state === self::ACTIVE;
    }

    /**
     * Check if user is customer
     *
     * @return bool
     */
    public function isCustomerAccount()
    {
        return $this->attributes['account_type'] === self::CUSTOMER;
    }

    /**
     * Check if user is staff
     *
     * @return bool
     */
    public function isStaffAccount()
    {
        return $this->attributes['account_type'] === self::STAFF;
    }

    /**
     * Delete user avatar in storage
     *
     * @return bool
     */
    public function deleteAvatarFile()
    {
        return Storage::delete('/public/' . $this->avatar);
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
