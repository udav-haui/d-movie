<?php

namespace App\Services;

use App\Helper\Data;
use App\Http\Requests\UserRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;

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
}
