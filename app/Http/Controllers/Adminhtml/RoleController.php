<?php

namespace App\Http\Controllers\Adminhtml;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignRequest;
use App\Http\Requests\RoleRequest;
use App\Role;
use App\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * @var RoleService
     */
    private $roleService;

    /**
     * RoleController constructor.
     * @param RoleService $roleService
     */
    public function __construct(
        RoleService $roleService
    ) {
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', Role::class);

        $roles = Role::all();
        return view('admin.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Role::class);

        return view('admin.role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(RoleRequest $request)
    {
        $this->authorize('create', Role::class);

        try {
            $this->roleService->store($request);
            return redirect(Role::REDIRECT)->with('success', __('Role have created success!'));
        } catch (\Exception $exception) {
            return back()->withErrors($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Role $role
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Role $role)
    {
        $this->authorize('update', Role::class);
        $permissions = $role->permissions->toArray();
        $permissionsArr = [];
        foreach ($permissions as $permission) {
            $permissionsArr[] = $permission['permission_code'];
        }
        $permissionsString = implode(',', $permissionsArr);
        return view('admin.role.edit', compact('role', 'permissionsString'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RoleRequest $request
     * @param Role $role
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(RoleRequest $request, Role $role)
    {
        $this->authorize('update', Role::class);
        try {
            if ($this->roleService->update($request, $role)) {
                return redirect(Role::REDIRECT)->with('success', __('Role have updated success.'));
            }
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {
        try {
            $this->roleService->delete($role);
            return response()->json([
                'status' => 200,
                'message' => __('Role have deleted success.')
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 400,
                'message' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Show Assign to user form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function showAssignForm()
    {
        $this->authorize('viewAny', Role::class);
        return view('admin.role.assign');
    }

    /**
     * Assign list user to a role
     *
     * @param AssignRequest $request
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function doAssign(AssignRequest $request)
    {
        $this->authorize('viewAny', Role::class);
        dd(request()->all());
    }

    /**
     * Fetch all role
     *
     * @return Role[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse
     */
    public function fetch()
    {
        return request()->ajax() ?
            response()->json([
                'data' => $this->roleService->fetch()
            ]) : $this->roleService->fetch();
    }

    public function get(Role $role)
    {
        return request()->ajax() ?
            response()->json([
                'data' => $role
            ]) : $role;
    }
}
