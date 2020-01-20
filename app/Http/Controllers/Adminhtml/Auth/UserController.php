<?php

namespace App\Http\Controllers\Adminhtml\Auth;

use App\Helper\Data;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Log;
use App\Services\UserService;
use App\User;
use Carbon\Carbon;
use Cache;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function index()
    {
        return redirect('/admin');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        // $this->authorize('update', $user);
        try {
            $this->userService->update($request, $user);
            return back()->with('success', __('User info have updated.'));
        } catch (AuthorizationException $exception) {
            return back()->with('change_info', 'active')->withError($exception->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
                $this->userService->setAvatar($user);
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
                $this->userService->changePassword($user);
            } catch (\Exception $exception) {
                return back()->with('change_pass', 'active')->withError($exception->getMessage())->withInput();
            }
            return back()->with('success', __('Password have successfully updated.'));
        }
    }

    public function getUsers()
    {
        $name = request()->name;
        if ($name) {
            echo $name;
        } else {
            $users = User::whereAccountType(User::NORMAL_USER)->whereState(User::ACTIVE)->get();
            return response()->json([
                'data'=> $users
            ]);
        }
    }
}
