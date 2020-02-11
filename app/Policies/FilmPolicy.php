<?php

namespace App\Policies;

use App\Repositories\Interfaces\FilmInterface as Film;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilmPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any films.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * User be can edit or update this model
     *
     * @param User $user
     * @return bool
     */
    public function canEditDelete(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess(Film::EDIT) ||
            $user->canAccess(Film::DELETE);
    }

    /**
     * Determine whether the user can view the film.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess(Film::VIEW);
    }

    /**
     * Determine whether the user can create films.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess(Film::CREATE);
    }

    /**
     * Determine whether the user can update the film.
     *
     * @param  \App\User  $user
     * @param  \App\Film  $film
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess(Film::EDIT);
    }

    /**
     * Determine whether the user can delete the film.
     *
     * @param  \App\User  $user
     * @param  \App\Film  $film
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess(Film::DELETE);
    }

    /**
     * Determine whether the user can restore the film.
     *
     * @param  \App\User  $user
     * @param  \App\Film  $film
     * @return mixed
     */
    public function restore(User $user, Film $film)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the film.
     *
     * @param  \App\User  $user
     * @param  \App\Film  $film
     * @return mixed
     */
    public function forceDelete(User $user, Film $film)
    {
        //
    }
}
