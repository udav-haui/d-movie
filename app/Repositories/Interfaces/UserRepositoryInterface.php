<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\UserRequest;
use App\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Class UserRepository
 *
 * @package App\Services
 */
interface UserRepositoryInterface
{
    /**
     * Get a user
     *
     * @param int $userId
     * @return mixed
     */
    public function get(int $userId);

    /**
     * Get list users
     *
     * @param array
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getActiveUsers($fields = [
        'account_type' => \App\User::STAFF,
        'state' => \App\User::ACTIVE
    ]);

    /**
     * Update user avatar
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|bool
     * @throws AuthorizationException
     * @throws Exception
     */
    public function setAvatar(User $user);

    /**
     * Update user password
     *
     * @param User $user
     * @return bool
     * @throws AuthorizationException
     * @throws Exception
     */
    public function changePassword(User $user);

    /**
     * Find a staff and active user collection provide by email, name or username
     *
     * @param string $searchText
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findUserByNameOrMailOrUsername($searchText);

    /**
     * @param string $attribute
     * @param string|int $value
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function addToFilter($attribute, $value, $query);

    /**
     * Get user by email
     *
     * @param string $email
     * @param int $accountType
     * @param int $state
     * @return mixed
     */
    public function findUserByEmail($email, $accountType = null, $state = null);

    /**
     * Get user by username
     *
     * @param string $username
     * @return mixed
     */
    public function findUserByUsername($username);

    /**
     * Get all active staff account
     *
     * @return mixed
     */
    public function fetchAllStaff();

    /**
     * Add field for insert
     *
     * @param string $attribute
     * @param string|int $value
     * @param array $fields
     * @return array
     */
    public function addToInsert($attribute, $value, $fields);
}
