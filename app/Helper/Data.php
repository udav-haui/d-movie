<?php

namespace App\Helper;

class Data
{
    /** @var string Define actions name */
    const CREATE = 'create';
    const UPDATE = 'update';
    const DELETE = 'delete';

    /** @var string define short message for log action */
    const CREATE_MSG = 'have created';
    const UPDATE_MSG = 'have updated';
    const DELETE_MSG = 'have deleted';

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
}
