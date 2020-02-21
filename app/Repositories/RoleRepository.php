<?php

namespace App\Repositories;

use App\Http\Requests\AssignRequest;
use App\Http\Requests\RoleRequest;
use App\Permission;
use App\Repositories\Abstracts\CRUDModelAbstract;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Role;
use App\User;

/**
 * Class RoleRepository
 *
 * @package App\Services
 */
class RoleRepository extends CRUDModelAbstract implements RoleRepositoryInterface
{
    use LoggerTrait;

    protected $model = Role::class;

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
            $role = $this->create([
                'role_name' => $request->role_name
            ]);

            if ($role) {
                if ($request->permissions != null) {
                    $permissions = explode(',', $request->permissions);
                    foreach ($permissions as $permission) {
                        $role->permissions()->create([
                            'permission_code' => $permission
                        ]);
                        // $this->createLog($createdPermission, Permission::class);
                    }
                }
            }
            return $role;
        } catch (\Exception $exception) {
            throw new \Exception(__('Ooops, something wrong appended.' . $exception->getMessage()));
        }
    }

    /**
     * Update A role
     *
     * @param null $roleId
     * @param Role $role
     * @param array $fields
     * @param bool $isWriteLog
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function update($roleId = null, $role = null, $fields = [], bool $isWriteLog = true)
    {
        try {
            if ($roleId !== null) {
                $role = $this->find($roleId);
            }
            $role->permissions()->delete();

            /** @var array $permissions */
            $permissions = explode(',', $fields['permissions']);

            foreach ($permissions as $permission) {
                $role->permissions()->create([
                    'permission_code' => $permission
                ]);
            }
            $role = parent::update(null, $role, ['role_name' => $fields['role_name']]);

            return $role;
        } catch (\Exception $exception) {
            throw new \Exception(__('Ooops, something wrong appended.') . ' - ' . $exception->getMessage());
        }
    }

    /**
     * Delete a role
     *
     * @param null|int|string $roleId
     * @param Role $role
     * @param bool $isWriteLog
     * @return bool
     * @throws \Exception
     */
    public function delete($roleId = null, $role = null, bool $isWriteLog = true)
    {
        if ($roleId !== null) {
            $role = $this->find($roleId);
        }
        try {
            $role->permissions()->delete();
            parent::delete(null, $role);
            return true;
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
     * @param string|int|null $roleId
     * @param Role $role
     * @param array $uids
     */
    public function doAssign($roleId = null, $role = null, $uids = [])
    {
        if ($roleId !== null) {
            /** @var Role $role */
            $role = $this->find($roleId);
        }

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
}
