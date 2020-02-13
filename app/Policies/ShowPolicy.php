<?php

namespace App\Policies;

use App\Repositories\Interfaces\ShowInterface as Show;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShowPolicy
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
            $user->canAccess(Show::EDIT) ||
            $user->canAccess(Show::DELETE);
    }

    /**
     * Determine whether the user can view the show.
     *
     * @param  User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess(Show::VIEW);
    }

    /**
     * Determine whether the user can create shows.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess(Show::CREATE);
    }

    /**
     * Determine whether the user can update the show.
     *
     * @param  User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess(Show::EDIT);
    }

    /**
     * Determine whether the user can delete the show.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess(Show::DELETE);
    }
}
