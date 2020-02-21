<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogPolicy extends ModelPolicyAbstract
{
    use HandlesAuthorization;

    protected $model = \App\Log::class;
}
