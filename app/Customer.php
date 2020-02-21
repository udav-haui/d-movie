<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    /**
     * Define role permission
     */
    const VIEW = 'customer-view';
    const CREATE = 'customer-create';
    const EDIT = 'customer-edit';
    const DELETE = 'customer-delete';
}
