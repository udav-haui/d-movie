<?php

namespace App\Policies;

use App\User;
use App\Config;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class ConfigPolicy
 *
 * @package App\Policies
 */
class ConfigPolicy extends ModelPolicyAbstract
{
    use HandlesAuthorization;

    protected $model = Config::class;

    /**
     * User can config payment methods section
     *
     * @param User $user
     * @return bool
     */
    public function salesPaymentMethods(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess(Config::SALE_PAYMENT_METHODS);
    }
}
