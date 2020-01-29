<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];

    /**
     * Define role permission
     */
    const ROLE_VIEW = 'role-view';
    const ROLE_CREATE = 'role-create';
    const ROLE_EDIT = 'role-edit';
    const ROLE_DELETE = 'role-delete';
    /**
     * A role has manny user use it
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * A role has many permissions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
