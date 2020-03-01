<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberRequest;
use App\Repositories\Interfaces\MemberRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\User;
use Illuminate\Http\Request;

/**
 * Class MemberController
 *
 * @package App\Http\Controllers\Frontend
 */
class MemberController extends Controller
{
    /**
     * @var MemberRepositoryInterface
     */
    private $memberRepository;
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * MemberController constructor.
     *
     * @param MemberRepositoryInterface $memberRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        MemberRepositoryInterface $memberRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->middleware('auth');
        $this->memberRepository = $memberRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        $member = auth()->user();
        return view('frontend.customer.profile', compact('member'));
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
        return \Validator::make(
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
     * Update member password
     * @param User $member
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function changePassword(User $member)
    {
        $this->authorize('selfUpdate', $member);

        $validator = $this->validator(request()->all());
        if ($validator->fails()) {
            return request()->ajax() ?
                response()->json([
                    'status' => 404,
                    'message' => $validator->errors()
                ]) :
                back()->withErrors($validator->errors());
        }
        if ($validator->validated()) {
            try {
                $this->userRepository->changePassword($member);

                return request()->ajax() ?
                    response()->json([
                        'status' => 200,
                        'message' => __('You save your :name', ['name' => __('password')])
                    ]) :
                    redirect(route('member.show', ['slug' => __('membership')]));
            } catch (\Exception $e) {
                return request()->ajax() ?
                    response()->json([
                        'status' => 404,
                        'message' => $e->getMessage()
                    ]) :
                    back()->with('error', $e->getMessage());
            }
        }
    }

    /**
     * @param MemberRequest $request
     * @param User $member
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function changeInformation(MemberRequest $request, User $member)
    {
        $this->authorize('selfUpdate', $member);

        try {
            $data = $request->all();

            if (array_key_exists(User::EMAIL, $data)) {
                unset($data[User::EMAIL]);
            }

            $member = $this->memberRepository->update(null, $member, $data);
            $message = __('You save your :name', ['name' => __('information')]);
            return request()->ajax() ?
                response()->json([
                    'status' => 200,
                    'mesage' => $message
                ]) :
                back()->with('success', $message);

        } catch (\Exception $e) {
            return request()->ajax() ?
                response()->json([
                    'status' => 404,
                    'message' => $e->getMessage()
                ]) :
                back()->with('error', $e->getMessage());
        }
    }
}
