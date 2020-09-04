<?php

namespace App\Http\Controllers\Adminhtml;

use App\Exceptions\NoChangedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssignRequest;
use App\Http\Requests\RoleRequest;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Role;
use App\Repositories\RoleRepository;
use Config;
use Illuminate\Http\Request;

/**
 * Class RoleController
 *
 * @package App\Http\Controllers\Adminhtml
 */
class RoleController extends Controller
{
    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepository;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * RoleController constructor.
     * @param RoleRepositoryInterface $roleRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        RoleRepositoryInterface $roleRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
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
            $this->roleRepository->store($request);
            return redirect(route('roles.index'))->with('success', __('Role have created success!'));
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
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
        $users = $this->userRepository->getActiveUsers();
        $relatedUsersCollect = $role->users;
        $relatedUsers = [];
        foreach ($relatedUsersCollect as $user) {
            $relatedUsers[] = $user->id;
        }
        return view(
            'admin.role.edit',
            compact('role', 'permissionsString', 'users', 'relatedUsers')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RoleRequest $request
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws NoChangedException
     */
    public function update(RoleRequest $request, Role $role)
    {
        $this->authorize('update', Role::class);
        try {
            if ($this->roleRepository->update(null, $role, $request->all())) {
                return redirect(route('roles.index'))
                    ->with('success', __('Role have updated success.'));
            }
        } catch (NoChangedException $e) {
            throw $e;
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete', Role::class);

        try {
            $this->roleRepository->delete(null, $role);
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
     * @return \Illuminate\Http\RedirectResponse|void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function doAssign(AssignRequest $request)
    {
        $this->authorize('viewAny', Role::class);

        try {
            $this->roleRepository->doAssign($request->role, null, $request->user_ids);

            return redirect()->route('roles.index')
                ->with('success', __('Assign role to user successfully.'));
        } catch (\Exception $e) {
            return redirect()->route('roles.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Single assign role to user
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function doSingleAssign()
    {
        $this->authorize('viewAny', Role::class);

        try {
            $this->roleRepository->doSingleAssign();

            return request()->ajax() ?
                response()->json([
                    'status' => 200,
                    'message' => __('Assign role to user successfully.')
                ]) :
                back()->with('success', __('Assign role to user successfully.'));
        } catch (\Exception $e) {
            return request()->ajax() ?
                response()->json([
                    'status' => 404,
                    'message' => $e->getMessage()
                ]) :
                back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mass assign role to users
     *
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function massAssign(Role $role)
    {
        $this->authorize('viewAny', Role::class);

        $ids = request('ids');

        try {
            $this->roleRepository->doAssign(null, $role, $ids);
            return response()->json([
                'status' => 200,
                'message' => __('Assign role to user successfully.')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Fetch all role
     *
     * @return Role[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function fetch()
    {
        $this->authorize('viewAny', Role::class);

        return request()->ajax() ?
            response()->json([
                'data' => $this->roleRepository->fetch()
            ]) : $this->roleRepository->fetch();
    }

    /**
     * Get a role
     *
     * @param Role $role
     * @return Role|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function get(Role $role)
    {
        $this->authorize('viewAny', Role::class);
        return request()->ajax() ?
            response()->json([
                'data' => $role
            ]) : $role;
    }

    /**
     * Make a payment by momo
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function momoTest()
    {

    }



    public function momoCallback()
    {
        dd(request()->all());
    }
}
