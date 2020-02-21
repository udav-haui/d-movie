<?php

namespace App\Helper;

class Data
{
    /** Constant language value */
    const VIETNAM = 'vi';
    const ENGLISH = 'en';

    /** @var string Define actions name */
    const CREATE = 'create';
    const UPDATE = 'update';
    const DELETE = 'delete';

    /** @var string define short message for log action */
    const CREATE_MSG = 'have created';
    const UPDATE_MSG = 'have updated';
    const DELETE_MSG = 'have deleted';

    const ASSIGN_MSG = 'have assigned';
    const ASSIGN = 'assign';

    /**
     * Define storage path
     *
     * @var string
     */
    const STORAGE = '/storage/';

    /**
     * Define URL path
     *
     * @var string
     */
    const ADMIN_LOGIN_PATH = '/admin/login',
        ADMIN_PATH = '/admin',
        CUSTOMER_LOGIN_PATH = '/login';

    /**
     * Get admin prefix
     *
     * @return \Illuminate\Config\Repository|mixed|string
     */
    public static function getAdminPath()
    {
        return config('app.admin_path');
    }
}
