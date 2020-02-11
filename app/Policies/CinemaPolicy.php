<?php

namespace App\Policies;

use App\Repositories\Interfaces\CinemaInterface as Cinema;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CinemaPolicy
{
    use HandlesAuthorization;

    /**
     * User be can edit or update this model
     *
     * @param User $user
     * @return bool
     */
    public function canEditDelete(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess(Cinema::EDIT) ||
            $user->canAccess(Cinema::DELETE);
    }

    /**
     * Determine whether the user can view the cinema.
     *
     * @param  User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess(Cinema::VIEW);
    }

    /**
     * Determine whether the user can create cinemas.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess(Cinema::CREATE);
    }

    /**
     * Determine whether the user can update the cinema.
     *
     * @param  User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess(Cinema::EDIT);
    }

    /**
     * Determine whether the user can delete the cinema.
     *
     * @param  User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess(Cinema::DELETE);
    }
}
