<?php

namespace App\Policies;

use App\Dashboard;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class DashboardPolicy
 *
 * @package App\Policies
 */
class DashboardPolicy extends ModelPolicyAbstract
{
    use HandlesAuthorization;

    protected $model = Dashboard::class;
}
