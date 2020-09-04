<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends AbstractModel
{
    protected $guarded = [];

    /**
     * A permission belong to a role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
