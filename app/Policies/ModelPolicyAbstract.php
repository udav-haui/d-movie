<?php

namespace App\Policies;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModelPolicyAbstract
 *
 * @package App\Policies
 */
abstract class ModelPolicyAbstract
{
    /** @var Model */
    protected $model;

    /**
     * User be can edit or update this model
     *
     * @param User $user
     * @return bool
     */
    public function canEditDelete(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess($this->model::EDIT) ||
            $user->canAccess($this->model::DELETE);
    }

    /**
     * Determine whether the user can view the show.
     *
     * @param User $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess($this->model::VIEW);
    }

    /**
     * Determine whether the user can create shows.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess($this->model::CREATE);
    }

    /**
     * Determine whether the user can update the show.
     *
     * @param User $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess($this->model::EDIT);
    }

    /**
     * Determine whether the user can delete the show.
     *
     * @param User $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess($this->model::DELETE);
    }
}
