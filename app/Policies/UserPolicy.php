<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy extends ModelPolicyAbstract
{
    use HandlesAuthorization;

    protected $model = User::class;

    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin() || $user->canAccess(User::VIEW);
    }

    /**
     * Determine whether the both user has permission can change username of the other user.
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function updateUserName(User $user, User $model)
    {
        return $user->isAdmin() ||
            $user->canAccess(User::EDIT) ||
            ($user->getAuthIdentifier() === $model->getAuthIdentifier() &&
            $model->canChangeUsername());
    }

    /**
     * Determine whether the user can change it'self model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function selfUpdate(User $user, User $model)
    {
        return $user->isAdmin() ||
            $user->canAccess(User::EDIT) ||
            $user->getAuthIdentifier() === $model->getAuthIdentifier();
    }

    /**
     * Determine whether the user can change it'self model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function cannotSelfUpdate(User $user, User $model)
    {
        return $user->isAdmin() ||
            ($user->canAccess(User::EDIT) &&
            $user->getAuthIdentifier() !== $model->getAuthIdentifier());
    }
}
