<?php

namespace App\Policies;

use App\Cinema;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CinemaPolicy extends ModelPolicyAbstract
{
    use HandlesAuthorization;

    protected $model = Cinema::class;
}
