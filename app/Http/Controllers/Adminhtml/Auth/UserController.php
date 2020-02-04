<?php

namespace App\Http\Controllers\Adminhtml\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\User;
use Cache;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepository;

    /**
     * UserController constructor.
     *
     * @param UserRepositoryInterface $userRepository
     * @param RoleRepositoryInterface $roleRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        RoleRepositoryInterface $roleRepository
    ) {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = $this->userRepository->all();
        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', User::class);

        $roles = $this->roleRepository->all();
        return view('admin.user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws AuthorizationException
     */
    public function store(CreateUserRequest $request)
    {
        $this->authorize('create', User::class);

        $fields = [
            'account_type' => User::STAFF,
            'can_change_username' => User::CAN_CHANGE_USERNAME,
            'login_with_social_account' => User::NORMAL_LOGIN,
            'username' => $request->username,
            'name' => $request->name ?? null,
            'email' => $request->email ?? null,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'phone' => $request->phone ?? null,
            'address' => $request->address,
            'dob' => $request->dob ? $this->userRepository->formatDate($request->dob) : null,
            'state' => User::ACTIVE,
            'description' => $request->description ?? null,
            'role_id' => $request->role ?? null
        ];

        $this->userRepository->create($fields);

        return redirect(route('users.index'))->with('success', __('User has created success.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        /**
         * Make instance from namespace string
         *
         * $log = Log::find(3); $target = $log->target; $instance = new $target();
         */
//        $log = Log::find(1);
//        $target = $log->target_model;
//        $instance = new $target();
//        $array = json_decode($log->message, true);
//        $u = new User();
//        $u->fill($array);
//        dd($u->id, $array, $instance->find($log->target_id) ,$log->message);
        return view('admin.auth.profile', compact('user'));
    }

    /**
     * Show the form for editing the user.
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws AuthorizationException
     */
    public function edit(User $user)
    {
        $this->authorize('selfUpdate', $user);

        $roles = $this->roleRepository->all();
        return view('admin.user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UserRequest $request, User $user)
    {
        $this->authorize('selfUpdate', $user);

        try {
            $this->userRepository->update($request, $user);
            return back()->with('success', __('User info have updated.'));
        } catch (AuthorizationException $exception) {
            return back()->with('change_info', 'active')
                ->withError($exception->getMessage())->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function manageUpdate(UpdateUserRequest $request, User $user)
    {
        $this->authorize('selfUpdate', $user);

        $fields = [
            'username' => $request->username,
            'name' => $request->name ?? null,
            'email' => $request->email ?? null,
            'gender' => $request->gender,
            'phone' => $request->phone ?? null,
            'address' => $request->address,
            'dob' => $request->dob ? $this->userRepository->formatDate($request->dob) : null,
            'description' => $request->description ?? null,
        ];

        // expr1 ?: expr2 , return expr1 if expr1 is true and expr2 when expr1 false
        !auth()->user()->isAdmin() ?: $fields['role_id'] = $request->role ?? null;
        if (auth()->user()->getAuthIdentifier() === $user->getAuthIdentifier() &&
            !auth()->user()->can('update', User::class)
        ) {
            unset($fields['username']);
        }

        if ($request->has('changePass')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'password' => 'required|min:6'
                ],
                [
                    'password.required' => __('You must input :attribute'),
                    'password.min' => __(':attribute need at least :min character')
                ],
                [
                    'password' => __('Password')
                ]
            );
            if ($validator->fails()) {
                return back()->withErrors($validator->getMessageBag())->withInput();
            }
            $fields['password'] = Hash::make($request->password);
        }
        try {
            $this->userRepository->update($request, $user, $fields);
            return redirect(route('users.index'))->with('success', __('User info have updated.'));
        } catch (\Exception $exception) {
            return back()->with('error', __('User info have updated.'))->withInput();
        }
    }

    /**
     * Update user state
     */
    public function changeState()
    {
        $this->authorize('update', User::class);

        /** @var int $userId */
        $userId = request()->user;

        /** @var User $user */
        $user = $this->userRepository->get($userId);
        $this->userRepository->update(request(), $user, ['state' => request('state')]);

        return response()->json([
            'status' => 200,
            'message' => __('User\'s state changed!')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws AuthorizationException
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', User::class);

        try {
            if ($this->userRepository->destroy($user)) {
                return request()->ajax() ? response()->json([
                    'status' => 200,
                    'message' => __('The user has been deleted.')
                ]) : back()->with('success', __('The user has been deleted.'));
            } else {
                return request()->ajax() ? response()->json([
                    'status' => 400,
                    'message' => __('Ooops, something wrong appended.')
                ]) : back()->with('error', __('Ooops, something wrong appended.'));
            }
        } catch (\Exception $exception) {
            return request()->ajax() ? response()->json([
                'status' => 400,
                'message' => $exception->getMessage()
            ]) : back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Set user avatar
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setAvatar(User $user)
    {
        try {
            $validator = Validator::make(
                request()->all(),
                [
                    'avatar' => 'required|image'
                ],
                [
                    'avatar.image' => __('Please input correct type of :attribute.'),
                    'avatar.required' => __('Please select a file.')
                ],
                [
                    'avatar' => __('Avatar')
                ]
            );
            if ($validator->fails()) {
                $validator->errors()->add('change_avatar', 'active');
                return back()->withErrors($validator->errors()->getMessages());
            } else {
                $this->userRepository->setAvatar($user);
                return back()->with('success', __('Avatar updated success.'));
            }
        } catch (\Exception $exception) {
            return back()->with('change_avatar', 'active')->withError($exception->getMessage())->withInput();
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $roles = [
            'current_password' => [
                'required'
            ],
            'password' => [
                'required',
                'min:8',
                'confirmed'
            ],
        ];
        if (!request()->has('current_password')) {
            unset($roles['current_password']);
        }
        return Validator::make(
            $data,
            $roles,
            [
                'current_password.required' => __('You must input :attribute'),
                'password.required' => __('You must input :attribute'),
                'password.min' => __('You can not input less than :min character'),
                'password.confirmed' => __('The :attribute does not match.')
            ],
            [
                'current_password' => __('Current Password'),
                'password' => __('Password'),
                'password_confirmation' => __('Password confirmation')
            ]
        );
    }

    /**
     * Change user password
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(User $user)
    {
        $validator = $this->validator(request()->all());
        if ($validator->fails()) {
            $validator->errors()->add('change_pass', 'active');
            return back()->withErrors($validator->errors()->getMessages());
        } else {
            try {
                $this->userRepository->changePassword($user);
            } catch (\Exception $exception) {
                return back()->with('change_pass', 'active')->withError($exception->getMessage())->withInput();
            }
            return back()->with('success', __('Password have successfully updated.'));
        }
    }

    /**
     * Get list staff and active users by username, email or name
     *
     * @return \App\Repositories\Collection|\Illuminate\Http\JsonResponse
     */
    public function findUserByNameOrMailOrUsername()
    {
        $name = request()->name;
        if ($name) {
            $users = $this->userRepository->findUserByNameOrMailOrUsername($name);
        } else {
            $users = $this->userRepository->fetchAllStaff();
        }
        return request()->ajax() ? response()->json([
            'data'=> $users
        ]) : $users;
    }

    public function getActiveUsers()
    {
        $field = request()->all();
        $users = $this->userRepository->getListActiveUser($field);
        return request()->ajax() ? response()->json([
            'data' => $users
        ]) : $users;
    }
}
