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

    /* Constant user column */
    const ID = 'id';
    const ACCOUNT_TYPE = 'account_type';
    const CAN_CHANGE_USERNAME = 'can_change_username';
    const LOGIN_WITH_SOCIAL_ACCOUNT = 'login_with_social_account';
    const USERNAME = 'username';
    const NAME = 'name';
    const EMAIL = 'email';
    const PASSWORD = 'password';
    const GENDER = 'gender';
    const PHONE = 'phone';
    const ADDRESS = 'address';
    const AVATAR = 'avatar';
    const DOB = 'dob';
    const STATE = 'state';
    const DESCRIPTION = 'description';
    const ROLE_ID = 'role_id';

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
        CAN = 1,
        CANT = 0,
        NORMAL_LOGIN = 0;

    /** Other */
    const MALE = 0,
        FEMALE = 1,
        OTHER = 2;

    /**
     * Get user id
     *
     * @return string|int
     */
    public function getId()
    {
        return $this->getAttribute(self::ID);
    }

    /**
     * Set id
     *
     * @param string|int $id
     * @return void
     */
    public function setId($id)
    {
        $this->setAttribute(self::ID, $id);
    }

    /**
     * Get d-m-y formatted date
     *
     * @return string
     */
    public function getDobFormatted()
    {
        return $this->getDob() ? Carbon::make($this->getDob())->format('d-m-Y') : '';
    }

    /**
     * Get user date of birth
     *
     * @return null|string
     */
    public function getDob()
    {
        return $this->getAttribute(self::DOB);
    }

    /**
     * Set dob for user with input format d/m/y
     *
     * @param string $dob
     */
    public function setDob($dob)
    {
        $this->setAttribute(self::DOB, Carbon::make($dob)->format('Y-m-d'));
    }

    /**
     * Get user avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->getAttribute('avatar');
    }

    /**
     * Set avatar path for user
     *
     * @param string $avatar
     */
    public function setAvatar($avatar)
    {
        $this->setAttribute(self::AVATAR, $avatar);
    }

    /**
     * Get user avatar path
     *
     * @return null|string
     */
    public function getAvatarPath()
    {
        return $this->getAvatar() ? Data::STORAGE . $this->getAvatar() : '/images/icons/account.png';
    }

    /**
     * Get user avatar
     *
     * @return null|string
     */
    public function getRenderAvatarHtml()
    {
        return "<a href=\"{$this->getAvatarPath()}\"
            class=\"slide-item\" dm-fancybox
            data-fancybox=\"user-avatar\" data-caption=\"{$this->getName()}\">
            <img src=\"{$this->getAvatarPath()}\"
                 class=\"slide-item-image\" />
        </a>";
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
     * @return string
     */
    public function getAddress()
    {
        return $this->getAttribute(self::ADDRESS);
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
     * Get user gender code
     *
     * @return array|string|null
     */
    public function getGender()
    {
        return $this->getAttribute('gender');
    }

    /**
     * Get user gender name
     *
     * @return array|string|null
     */
    public function getGenderName()
    {
        return $this->getGender() === self::MALE ? __('Male') :
            ($this->getGender() === self::FEMALE ? __('Female') : __('Other'));
    }

    /**
     * Get name of user
     *
     * @return string
     */
    public function getName()
    {
        return $this->getAttribute(User::NAME);
    }

    /**
     * Get can change username code
     *
     * @return int
     */
    public function getCanChangeUsername()
    {
        return $this->getAttribute(self::CAN_CHANGE_USERNAME);
    }

    /**
     * Check if user can change username
     *
     * @return bool
     */
    public function canChangeUsername()
    {
        return $this->getCanChangeUsername() === self::CAN;
    }

    /**
     * Get username of user
     *
     * @return array|mixed|string|null
     */
    public function getUserName()
    {
        return $this->getAttribute('username');
    }
    /**
     * Get user status
     *
     * @return array|string|null
     */
    public function getStatusLabel()
    {
        return $this->getStatus() === self::ACTIVE ?
            __("Active") :
            ($this->getStatus() === self::NOT_VERIFY_BY_ADMIN ?
                __("Not verify") :
                __("Not active"));
    }

    /**
     * Get user status
     *
     * @return int|string
     */
    public function getStatus()
    {
        return $this->getAttribute('state');
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
        return $this->login_with_social_account === self::FIRST_LOGIN_WITH_SOCIAL_ACCOUNT;
    }

    /**
     * Check if user is active
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->getStatus() === self::ACTIVE;
    }

    /**
     * @return bool
     */
    public function isNotVerified()
    {
        return $this->getStatus() === self::NOT_VERIFY_BY_ADMIN;
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
        return Storage::delete('/public/' . $this->getAvatar());
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

    /**
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->getAttribute(self::EMAIL);
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->getAttribute(self::PHONE);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
