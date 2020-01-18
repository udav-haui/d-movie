<?php

namespace App\Services;

use App\Helper\Data;
use App\Http\Requests\UserRequest;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * Class UserService
 *
 * @package App\Services
 */
class UserService
{
    /**
     * Update a user
     *
     * @param UserRequest $request
     * @param User $user
     * @return bool
     * @throws AuthorizationException
     */
    public function update(UserRequest $request, User $user)
    {
        if (auth()->user()->can('update', $user)) {
            $userData = $request->all();
            if ($user->can_change_username == 0) {
                unset($userData['username']);
            } else {
                $userData['can_change_username'] = 0;
            }
            $dob = explode('/', $userData['dob']);
            $dob = Carbon::create((int)$dob[2], (int)$dob[1], (int)$dob[0]);
            $userData['dob'] = $dob->format('Y-m-d');
//            dd($userData['dob']);
            if ($user->update($userData)) {
                auth()->user()->logs()->create([
                    'short_message' => Data::UPDATE_MSG,
                    'message' => $user,
                    'action' => Data::UPDATE,
                    'target_model' => User::class,
                    'target_id' => $user->id
                ]);
            }
            return true;
        }
        throw new AuthorizationException(__('You can not do that :)'));
    }

    /**
     * Get list users
     *
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListUsers()
    {
        return User::where('id', '<>', auth()->user()->id)->get();
    }

    /**
     * Update user avatar
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|bool
     * @throws AuthorizationException
     * @throws Exception
     */
    public function setAvatar(User $user)
    {
        if (auth()->user()->can('update', $user)) {
            try {
                if ($user->avatar) {
                    Storage::delete('/public/' . $user->avatar);
                }
                $avtPath = request('avatar')->store('uploads', 'public');
                $data = [
                    'avatar' => $avtPath
                ];
                if ($user->update($data)) {
                    auth()->user()->logs()->create([
                        'short_message' => Data::UPDATE_MSG,
                        'message' => $user,
                        'action' => Data::UPDATE,
                        'target_model' => User::class,
                        'target_id' => $user->id
                    ]);
                }
                return true;
            } catch (Exception $exception) {
                Storage::delete('/public/' . $avtPath);
                throw new Exception(__('Something wrong!!!'));
            }
        }
        throw new AuthorizationException(__('You can not do that :)'));
    }

    /**
     * Update user password
     *
     * @param User $user
     * @return bool
     * @throws AuthorizationException
     * @throws Exception
     */
    public function changePassword(User $user)
    {
        if (auth()->user()->can('update', $user)) {
            if (request()->has('current_password')) {
                $currentPassword = request('current_password');
                if (Hash::check($currentPassword, $user->getAuthPassword())) {
                    $user->update([
                        'password' => Hash::make(request('password'))
                    ]);
                } else {
                    throw new Exception(__('Current password is not correct.'));
                }
            } else {
                $data = request()->all();
                $data['password'] = Hash::make($data['password']);
                unset($data['password_confirmation']);
                $data['login_with_social_account'] = 0;
                $user->update($data);
            }
            auth()->user()->logs()->create([
                'short_message' => Data::UPDATE_MSG,
                'message' => $user,
                'action' => Data::UPDATE,
                'target_model' => User::class,
                'target_id' => $user->id
            ]);
            return true;
        }
        throw new AuthorizationException(__('You can not do that :)'));
    }
}
