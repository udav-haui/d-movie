<?php

namespace App\Http\Controllers\Adminhtml\Auth;

use App\Customer;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Class UserController
 *
 * @package App\Http\Controllers\Adminhtml\Auth
 */
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

        if (request()->ajax()) {
            $users = $this->userRepository->all();

            $dataTable = datatables()->of($users);

            $dataTable->editColumn('avatar', function (User $user) {
                return '<div class="dmovie-flex-container">' . $user->getRenderAvatarHtml() . '</div>';
            });
            $dataTable->editColumn('gender', function (User $user) {
                return $user->getGenderName();
            });
            /** @var User $authUser */
            $authUser = auth()->user();
            if ($authUser->isAdmin()) {
                $dataTable->editColumn('role_id', function (User $user) {
                    return $user->getRoleName();
                });
            }

            $htmlRaw = "";
            $dataTable->addColumn('task', function (User $user) use ($authUser, $htmlRaw) {
                if ($authUser->can('selfUpdate', $user)) {
                    $htmlRaw .= "<a href=\"".route('users.edit', ['user' => $user->getId()]) . "\"
                                   type=\"button\"
                                   class=\"";
                    if ($authUser->getAuthIdentifier() === $user->getId() ||
                        $authUser->cant('delete', \App\User::class)
                    ) {
                        $htmlRaw .= "col-md-12";
                    } else {
                        $htmlRaw .= "col-md-6";
                    }
                    $htmlRaw .= " col-xs-12 btn dmovie-btn dmovie-btn-success\" title=\"";
                    $htmlRaw .= __('Detail');
                    $htmlRaw .= "\"><i class=\"mdi mdi-account-edit\"></i></a>";
                }

                if ($authUser->can('delete', User::class) &&
                    $authUser->getId() !== $user->getId()
                ) {
                    $htmlRaw .= "<button id=\"deleteUserBtn\" type=\"button\"
                                            class=\"col-md-6 col-xs-12 btn dmovie-btn btn-danger\"
                                            title=\"" . __('Delete') . "\"
                                            data-id=\"{$user->getId()}\"
                                            url=\"" . route('users.destroy', ['user' => $user->id]) ."\">
                                        <i class=\"mdi mdi-account-minus\"></i>
                                    </button>";
                }

                return $htmlRaw;
            });

            $dataTable->editColumn('state', function (User $user) {
                $authU = auth()->user();
                $htmlRaw = "<span class=\"status-text\">
                                {$user->getStatusLabel()}
                            </span>";
                if ($authU->can('cannotSelfUpdate', $user)) {
                    $htmlRaw .= "<i class=\"ti-reload\"
                                   data-id=\"{$user->getStatus()}\"
                                   user-id=\"{$user->getId()}\"
                                   cancel-text=\"" . __('Cancel') . "\"
                                   onclick=\"changeStatus(this, '{$user->getId()}', '" . __('Select state') . "', '" .
                        __('Not active') . "', '". __('Not verify') . "', '" . __('Active') . "');\"
                                   title=\"".__('Change status') ."\"
                                   scope=\"change-state\"></i>";
                }

                return $htmlRaw;
            });

            return $dataTable->rawColumns(['avatar', 'state', 'href', 'task'])->make();
        }

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
            USER::ACCOUNT_TYPE => User::STAFF,
            User::CAN_CHANGE_USERNAME => User::CAN,
            User::LOGIN_WITH_SOCIAL_ACCOUNT => User::NORMAL_LOGIN,
            USER::USERNAME => $request->username,
            User::NAME => $request->name ?? null,
            User::EMAIL => $request->email ?? null,
            User::PASSWORD => Hash::make($request->password),
            User::GENDER => $request->gender,
            User::PHONE => $request->phone ?? null,
            User::ADDRESS => $request->address,
            User::DOB => $request->dob ? Carbon::make($request->dob)->format('Y-m-d') : null,
            User::STATE => User::ACTIVE,
            User::DESCRIPTION => $request->description ?? null,
            User::ROLE_ID => (int)$request->role === 0 ? null : $request->role
        ];

        try {
            $this->userRepository->create($fields);

            return redirect(route('users.index'))->with('success', __('User has created success.'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function getProfile()
    {
        $user = auth()->user();
        return view('admin.auth.profile', compact('user'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function show(User $user)
    {
        if (auth()->user()->getAuthIdentifier() !== $user->getId() && !auth()->user()->isAdmin()) {
            return redirect(route('users.getProfile'));
        }
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
            $this->userRepository->update(null, $user, $request->all());
            return back()->with('success', __('User info have updated.'));
        } catch (\Exception $exception) {
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
            'dob' => $request->dob ? Carbon::make($request->dob)->format('Y-m-d') : null,
            'description' => $request->description ?? null,
        ];

        // expr1 ?: expr2 , return expr1 if expr1 is true and expr2 when expr1 false
        !auth()->user()->isAdmin() ?: $fields['role_id'] = ((int)$request->role === 0 ? null : $request->role);

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
            $this->userRepository->update(null, $user, $fields);
            return redirect(route('users.index'))->with('success', __('User info have updated.'));
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage())->withInput();
        }
    }

    /**
     * Mass update for multi users
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthorizationException
     */
    public function massUpdate()
    {
        $this->authorize('update', User::class);

        $users = request('users');
        $fields = request('fields');

        try {
            foreach ($users as $user) {
                $this->userRepository->update($user, null, $fields);
            }

            return response()->json([
                'status' => 200,
                'message' => __('Users info have updated.')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Mass update for a user
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthorizationException
     */
    public function massSingleUserUpdate(User $user)
    {
        $this->authorize('update', User::class);

        $fields = request()->all();

        try {
            $this->userRepository->update(null, $user, $fields);

            return response()->json([
                'status' => 200,
                'message' => __('User info have updated.')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
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

        $this->userRepository->update($userId, null, ['state' => request('state')]);

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
            if ($this->userRepository->delete(null, $user)) {
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
     * Multi destroy users
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws AuthorizationException
     */
    public function multiDestroy()
    {
        $this->authorize('delete', User::class);

        $ids = request('ids');

        try {
            /** @var string $id */
            foreach ($ids as $id) {
                $this->userRepository->delete($id);
            }

            $message = __('Users was deleted successfully.');
            return !request()->ajax() ?
                redirect(route('users.index'))
                    ->with('success', $message) :
                response()->json([
                    'status' => 200,
                    'message' => $message
                ]);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return !request()->ajax() ?
                back()->with(
                    'error',
                    $message
                ) :
                response()->json([
                    'status' => 400,
                    'message' => $message
                ]);
        }
    }

    /**
     * Multi change status of users
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws AuthorizationException
     */
    public function multiChangeStatus()
    {
        $this->authorize('update', User::class);


        $ids = request('ids');
        $status = request('status');

        try {
            /** @var string $id */
            foreach ($ids as $id) {
                if (!$this->userRepository->update($id, null, ['state' => $status])) {
                    $message = __('Ooops, something wrong appended.');

                    throw new \Exception($message);
                }
            }

            $message = __('The users status has been changed.');
            return request()->ajax() ? response()->json([
                'status' => 200,
                'message' => $message
            ]) : back()->with('success', $message);

        } catch (\Exception $e) {
            return request()->ajax() ? response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]) : back()->with('error', $e->getMessage());
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
     * @return \Illuminate\Http\JsonResponse
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

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthorizationException
     */
    public function getUsersByIds()
    {
        $this->authorize('view', User::class);

        $users = request('users');

        $searchUsers = $this->userRepository->getByIds($users);

        return request()->ajax() ?
            response()->json([
                'status' => 200,
                'data' => $searchUsers
            ]) : $searchUsers;
    }


    /** CUSTOMER */

    /**
     * Get Customer index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws AuthorizationException
     */
    public function customerIndex()
    {
        $this->authorize('view', Customer::class);

        $customers = $this->userRepository->getFilter(null, [
            User::ACCOUNT_TYPE => User::CUSTOMER
        ])->get();

        if (request()->ajax()) {
            $dataTable = datatables()->of($customers);

            $dataTable->editColumn('avatar', function (User $customer) {
                return '<div class="dmovie-flex-container">' . $customer->getRenderAvatarHtml() . '</div>';
            });
            $dataTable->editColumn('gender', function (User $customer) {
                return $customer->getGenderName();
            });
            /** @var User $authUser */
            $authUser = auth()->user();
            if ($authUser->isAdmin()) {
                $dataTable->editColumn('role_id', function (User $customer) {
                    return $customer->getRoleName();
                });
            }

            $htmlRaw = "";
            $dataTable->addColumn('task', function (User $customer) use ($authUser, $htmlRaw) {
                if ($authUser->can('update', Customer::class)) {
                    $htmlRaw .= "<a href=\"".route('users.customer.edit', ['customer' => $customer->getId()]) . "\"
                                   type=\"button\"
                                   class=\"";
                    if ($authUser->cant('delete', \App\User::class)) {
                        $htmlRaw .= "col-md-12";
                    } else {
                        $htmlRaw .= "col-md-6";
                    }
                    $htmlRaw .= " col-xs-12 btn dmovie-btn dmovie-btn-success\" title=\"";
                    $htmlRaw .= __('Detail');
                    $htmlRaw .= "\"><i class=\"mdi mdi-account-edit\"></i></a>";
                }

                if ($authUser->can('delete', User::class)) {
                    $htmlRaw .= "<button id=\"deleteUserBtn\" type=\"button\"
                                            class=\"col-md-6 col-xs-12 btn dmovie-btn btn-danger\"
                                            title=\"" . __('Delete') . "\"
                                            data-id=\"{$customer->getId()}\"
                                            url=\"" . route('users.destroy', ['user' => $customer->getId()]) ."\">
                                        <i class=\"mdi mdi-account-minus\"></i>
                                    </button>";
                }

                return $htmlRaw;
            });

            $dataTable->editColumn('state', function (User $customer) {
                $authU = auth()->user();
                $htmlRaw = "<span class=\"status-text\">
                                {$customer->getStatusLabel()}
                            </span>";
                if ($authU->can('cannotSelfUpdate', $customer)) {
                    $htmlRaw .= "<i class=\"ti-reload\"
                                   data-id=\"{$customer->getStatus()}\"
                                   user-id=\"{$customer->getId()}\"
                                   cancel-text=\"" . __('Cancel') . "\"
                                   onclick=\"changeStatus(this, '{$customer->getId()}', '" . __('Select state') . "', '" .
                        __('Not active') . "', '". __('Not verify') . "', '" . __('Active') . "');\"
                                   title=\"".__('Change status') ."\"
                                   scope=\"change-state\"></i>";
                }

                return $htmlRaw;
            });

            return $dataTable->rawColumns(['avatar', 'state', 'href', 'task'])->make();
        }

        return view('admin.customer.index');
    }

    /**
     * Get customer create form
     *
     * @throws AuthorizationException
     */
    public function customerCreate()
    {
        $this->authorize('create', Customer::class);

        return view('admin.customer.create');
    }

    /**
     * Store new customer
     *
     * @param CustomerRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws AuthorizationException
     */
    public function customerStore(CustomerRequest $request)
    {
        $this->authorize('create', Customer::class);

        try {
            $fields = [
                USER::ACCOUNT_TYPE => User::CUSTOMER,
                User::CAN_CHANGE_USERNAME => User::CAN,
                User::LOGIN_WITH_SOCIAL_ACCOUNT => User::NORMAL_LOGIN,
                USER::USERNAME => $request->username,
                User::NAME => $request->name ?? null,
                User::EMAIL => $request->email ?? null,
                User::PASSWORD => Hash::make($request->password),
                User::GENDER => $request->gender,
                User::PHONE => $request->phone ?? null,
                User::ADDRESS => $request->address,
                User::DOB => $request->dob ? Carbon::make($request->dob)->format('Y-m-d') : null,
                User::STATE => User::ACTIVE,
                User::DESCRIPTION => $request->description ?? null
            ];

            /** @var User $customer */
            $customer = $this->userRepository->create($fields);

            return redirect(route('users.customer.index'))->with('success', __('Customer [<code>:username</code>] has created.', ['username' => $customer->getUserName()]));

        } catch (\Exception $e) {
            back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * @param User $customer
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws AuthorizationException
     */
    public function customerEdit(User $customer)
    {
        $this->authorize('update', Customer::class);

        return view('admin.customer.edit', compact('customer'));
    }

    public function customerUpdate(CustomerRequest $request, User $customer)
    {
        $this->authorize('update', Customer::class);

        try {
            $fields = $request->all();
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
            /** @var User $customer */
            $customer = $this->userRepository->update(null, $customer, $fields);


            return redirect(route('users.customer.index'))->with(
                'success',
                __('The customer [<code>:username</code>] have updated.', ['username' => $customer->getUserName()])
            ) ;

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
