<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin() || $user->canAccess(\App\User::VIEW);
    }

    /**
     * Determine whether the both user has EDIT, DELETE permission can change the model.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function canEditDelete(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess(\App\User::EDIT) ||
            $user->canAccess(\App\User::DELETE);
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
            $user->canAccess(\App\User::EDIT) ||
            ($user->getAuthIdentifier() === $model->getAuthIdentifier() &&
            $model->canChangeUsername());
    }

    /**
     * Determine whether the user can change it'self model.
     *
     * @param \App\User $user
     * @param User $model
     * @return mixed
     */
    public function selfUpdate(User $user, User $model)
    {
        return $user->isAdmin() ||
            $user->canAccess(\App\User::EDIT) ||
            $user->getAuthIdentifier() === $model->getAuthIdentifier();
    }

    /**
     * Determine whether the user can change it'self model.
     *
     * @param \App\User $user
     * @param User $model
     * @return mixed
     */
    public function cannotSelfUpdate(User $user, User $model)
    {
        return $user->isAdmin() ||
            ($user->canAccess(\App\User::EDIT) &&
            $user->getAuthIdentifier() !== $model->getAuthIdentifier());
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\User $user
     * @param \App\User $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        return $user->isAdmin() ||
            $user->canAccess(\App\User::VIEW);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin() || $user->canAccess(\App\User::CREATE);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess(\App\User::EDIT);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->isAdmin() || $user->canAccess(\App\User::DELETE);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
