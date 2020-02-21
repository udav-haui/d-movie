<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class StaticPagePolicy
 *
 * @package App\Policies
 */
class StaticPagePolicy extends ModelPolicyAbstract
{
    use HandlesAuthorization;

    protected $model = \App\StaticPage::class;
}
