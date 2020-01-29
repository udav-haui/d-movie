<?php

namespace App\Repositories\Interfaces;


use App\Http\Requests\AssignRequest;
use App\Http\Requests\RoleRequest;
use App\Role;
use App\User;

/**
 * Class RoleRepository
 *
 * @package App\Services
 */
interface RoleRepositoryInterface
{
    /**
     * Store new roles
     *
     * @param RoleRequest $request
     * @return bool
     * @throws \Exception
     */
    public function store(RoleRequest $request);

    /**
     * Update A role
     *
     * @param RoleRequest $request
     * @param Role $role
     * @return bool
     * @throws \Exception
     */
    public function update(RoleRequest $request, Role $role);

    /**
     * Delete a role
     *
     * @param Role $role
     * @return bool
     * @throws \Exception
     */
    public function delete(Role $role);

    /**
     * Get list roles
     *
     * @return Role[]|\Illuminate\Database\Eloquent\Collection
     */
    public function fetch();

    /**
     * @param AssignRequest $request
     */
    public function doAssign(AssignRequest $request);

    /**
     * Assign a role to a user
     *
     * @param Role $role
     * @param User $user
     */
    public function assignUser(Role $role, User $user);

    /**
     * @param int $roleId
     * @return Role
     */
    public function getRole($roleId);

    /**
     * Get all role
     *
     * @return Role[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all();
}
