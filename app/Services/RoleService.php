<?php

namespace App\Services;

use App\Helper\Data;
use App\Http\Requests\RoleRequest;
use App\Permission;
use App\Role;

class RoleService
{
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
                auth()->user()->logs()->create([
                    'short_message' => Data::CREATE_MSG,
                    'message' => $role,
                    'action' => Data::CREATE,
                    'target_model' => Role::class,
                    'target_id' => $role->id
                ]);
                if ($request->permissions != null) {
                    $permissions = explode(',', $request->permissions);
                    foreach ($permissions as $permission) {
                        $CreatedPermission = $role->permissions()->create([
                            'permission_code' => $permission
                        ]);
                        auth()->user()->logs()->create([
                            'short_message' => Data::CREATE_MSG,
                            'message' => $CreatedPermission,
                            'action' => Data::CREATE,
                            'target_model' => Permission::class,
                            'target_id' => $CreatedPermission->id
                        ]);
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
                auth()->user()->logs()->create([
                    'short_message' => Data::UPDATE_MSG,
                    'message' => $role,
                    'action' => Data::UPDATE,
                    'target_model' => Role::class,
                    'target_id' => $role->id
                ]);
                $role->permissions()->delete();
                $permissions = explode(',', $request->permissions);
                foreach ($permissions as $permission) {
                    $updatedPermission = $role->permissions()->create([
                        'permission_code' => $permission
                    ]);
                    auth()->user()->logs()->create([
                        'short_message' => Data::CREATE_MSG,
                        'message' => $updatedPermission,
                        'action' => Data::CREATE,
                        'target_model' => Permission::class,
                        'target_id' => $updatedPermission->id
                    ]);
                }
                return true;
            }
        } catch (\Exception $exception) {
            throw new \Exception(__('Ooops, something wrong appended.') . ' - ' . $exception->getMessage());
        }
    }

    public function delete(Role $role)
    {
        try {
            $role->permissions()->delete();
            $oldRole = $role;
            if ($role) {
                if ($role->delete()) {
                    auth()->user()->logs()->create([
                        'short_message' => Data::DELETE_MSG,
                        'message' => $oldRole,
                        'action' => Data::DELETE,
                        'target_model' => Role::class,
                        'target_id' => $oldRole->id
                    ]);
                }
                return true;
            }
        } catch (\Exception $exception) {
            throw new \Exception(__('Ooops, something wrong appended.' . $exception->getMessage()));
        }
    }
}
