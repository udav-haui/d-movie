<?php

namespace App\Policies;

use App\Show;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShowPolicy extends ModelPolicyAbstract
{
    use HandlesAuthorization;

    protected $model = Show::class;
}
