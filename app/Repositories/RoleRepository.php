<?php

namespace App\Repositories;

use App\Http\Requests\AssignRequest;
use App\Http\Requests\RoleRequest;
use App\Permission;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Role;
use App\User;

/**
 * Class RoleRepository
 *
 * @package App\Services
 */
class RoleRepository implements RoleRepositoryInterface
{
    use LoggerTrait;

    /**
     * Store new roles
     *
     * @param RoleRequest $request
     * @return bool
     * @throws \Exception
     */
    public function store(RoleRequest $request)
    {
        try {
            $role = Role::create([
                'role_name' => $request->role_name
            ]);
            if ($role) {
                $this->createLog($role, Role::class);
                if ($request->permissions != null) {
                    $permissions = explode(',', $request->permissions);
                    foreach ($permissions as $permission) {
                        $createdPermission = $role->permissions()->create([
                            'permission_code' => $permission
                        ]);
                        $this->createLog($createdPermission, Permission::class);
                    }
                }
                return true;
            }
        } catch (\Exception $exception) {
            throw new \Exception(__('Ooops, something wrong appended.' . $exception->getMessage()));
        }
    }

    /**
     * Update A role
     *
     * @param RoleRequest $request
     * @param Role $role
     * @return bool
     * @throws \Exception
     */
    public function update(RoleRequest $request, Role $role)
    {
        try {
            $updatedRole = $role->update([
                'role_name' => $request->role_name
            ]);
            if ($updatedRole) {
                $this->updateLog($role, Role::class);

                $role->permissions()->delete();

                /** @var array $permissions */
                $permissions = explode(',', $request->permissions);

                foreach ($permissions as $permission) {
                    $updatedPermission = $role->permissions()->create([
                        'permission_code' => $permission
                    ]);

                    $this->createLog($updatedPermission, Permission::class);
                }
                /** @var \Illuminate\Database\Eloquent\Collection $relatedUsers */
                $relatedUsers = $role->users;

                // Dissociate all current related user
                foreach ($relatedUsers as $user) {
                    $user->role()->dissociate();
                    $user->save();
                }

                if ($request->has('users')) {

                    /** @var array $userIds */
                    $userIds = $request->users;

                    foreach ($userIds as $id) {
                        $user = User::find($id);

                        $this->assignUser($role, $user);
                    }
                }
                return true;
            }
        } catch (\Exception $exception) {
            throw new \Exception(__('Ooops, something wrong appended.') . ' - ' . $exception->getMessage());
        }
    }

    /**
     * Delete a role
     *
     * @param Role $role
     * @return bool
     * @throws \Exception
     */
    public function delete(Role $role)
    {
        try {
            $role->permissions()->delete();
            $oldRole = $role;
            if ($role) {
                if ($role->delete()) {
                    $this->deleteLog($oldRole, Role::class);
                }
                return true;
            }
        } catch (\Exception $exception) {
            throw new \Exception(__('Ooops, something wrong appended.' . $exception->getMessage()));
        }
    }

    /**
     * Get list roles
     *
     * @return Role[]|\Illuminate\Database\Eloquent\Collection
     */
    public function fetch()
    {
        $name = request('role_name');
        if (!$name) {
            return Role::all();
        } else {
            return Role::where('role_name', 'like', '%' . $name . '%')->get();
        }
    }

    /**
     * @param AssignRequest $request
     */
    public function doAssign(AssignRequest $request)
    {
        /** @var Role $role */
        $role = $this->getRole($request->role);

        /** @var array $uids */
        $uids = $request->user_ids;

        foreach ($uids as $uid) {
            /** @var User $user */
            $user = User::find($uid);

            $this->assignUser($role, $user);
        }
    }

    /**
     * Assign single role to user
     */
    public function doSingleAssign()
    {
        $roleId = request()->role;
        $role = Role::find($roleId);

        $userId = request()->user;
        $user = User::find($userId);

        $this->assignUser($role, $user);
    }
    /**
     * Assign a role to a user
     *
     * @param Role $role
     * @param User $user
     */
    public function assignUser(Role $role, User $user)
    {
        $user->role()->associate($role);

        $user->save();

        $this->assignLog($user, User::class);
    }

    /**
     * @param int $roleId
     * @return Role
     */
    public function getRole($roleId)
    {
        return Role::findOrFail($roleId);
    }

    /**
     * Get all role
     *
     * @return Role[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return Role::all();
    }
}
