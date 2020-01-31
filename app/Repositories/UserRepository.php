<?php

namespace App\Repositories;

use App\Helper\Data;
use App\Http\Requests\UserRequest;
use App\Repositories\Interfaces\LogRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * Class UserRepository
 *
 * @package App\Services
 */
class UserRepository implements UserRepositoryInterface
{
    use LoggerTrait;

    /**
     * @var LogRepositoryInterface
     */
    private $logRepository;

    /**
     * UserRepository constructor.
     *
     * @param LogRepositoryInterface $logRepository
     */
    public function __construct(
        LogRepositoryInterface $logRepository
    ) {
        $this->logRepository = $logRepository;
    }

    /**
     * Update a user
     *
     * @param $request
     * @param User $user
     * @param array $otherFields
     * @return bool
     */
    public function update($request, User $user, $otherFields = [])
    {
        if (!count($otherFields)) {
            /** @var array $userData */
            $userData = $request->all();

            if (auth()->user()->getAuthIdentifier() === $user->getAuthIdentifier()) {
                if (auth()->user()->cant('update', $user)) {
                    if (!$user->canChangeUsername()) {
                        unset($userData['username']);
                    } else {
                        $userData['can_change_username'] = 0;
                    }
                }
            }

            $dob = explode('/', $userData['dob']);

            $dob = Carbon::create((int)$dob[2], (int)$dob[1], (int)$dob[0]);

            $userData['dob'] = $dob->format('Y-m-d');

        } else {
            $userData = $otherFields;
        }

        if ($user->update($userData)) {
            $this->updateLog($user, User::class);
        }

        return true;
    }

    /**
     * Get a user
     *
     * @param int $userId
     * @return mixed
     */
    public function get(int $userId)
    {
        return User::find($userId);
    }

    /**
     * Get list users
     *
     * @param array $fields
     * @return User[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse
     */
    public function getActiveUsers($fields = [
        'account_type' => \App\User::STAFF,
        'state' => \App\User::ACTIVE
    ])
    {
        $users = User::query();

        foreach ($fields as $field => $value) {
            $this->addToFilter($field, $value, $users);
        }
        return $users->get();
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
        if (auth()->user()->can('canSelfUpdate', $user)) {
            try {
                if ($user->avatar) {
                    Storage::delete('/public/' . $user->avatar);
                }
                try {
                    $avtPath = request('avatar')->store('uploads', 'public');
                } catch (Exception $fileException) {
                    throw new Exception(__('Không thể tải ảnh của bạn lên! Xin lỗi!'));
                }
                $data = [
                    'avatar' => $avtPath
                ];
                if ($user->update($data)) {
                    $this->updateLog($user, User::class);
                } else {
                    Storage::delete('/public/' . $avtPath);
                    throw new Exception(__('Something happen when we store you avatar.'));
                }
                return true;
            } catch (Exception $exception) {
                throw new Exception(__('Something wrong: ') . $exception->getMessage());
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
        if (auth()->user()->can('canSelfUpdate', $user)) {
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
            $this->updateLog($user, User::class);
            return true;
        }
        throw new AuthorizationException(__('You can not do that :)'));
    }

    /**
     * Find a staff and active user collection provide by email, name or username
     *
     * @param string $searchText
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findUserByNameOrMailOrUsername($searchText)
    {
        $users = User::whereAccountType(User::STAFF)->whereState(User::ACTIVE)
            ->where(function ($query) use ($searchText) {
                $query->where('email', 'like', '%' . $searchText . '%')
                    ->orWhere('name', 'like', '%' . $searchText . '%')
                    ->orWhere('username', 'like', '%' . $searchText . '%');
            })->get();
        return $users;
    }

    /**
     * @param string $attribute
     * @param string|int $value
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function addToFilter($attribute, $value, $query)
    {
        $query->where($attribute, $value);

        return $query;
    }

    /**
     * Get user by email
     *
     * @param string $email
     * @param int $accountType
     * @param int $state
     * @return mixed
     */
    public function findUserByEmail($email, $accountType = null, $state = null)
    {
        $user = User::query();

        $this->addToFilter('email', $email, $user);
        if ($accountType) {
            $this->addToFilter('account_type', $accountType, $user);
        }
        if ($state) {
            $this->addToFilter('state', $state, $user);
        }
        return $user->get();
    }

    /**
     * Get user by username
     *
     * @param string $username
     * @return mixed
     */
    public function findUserByUsername($username)
    {
        return User::whereUsername($username)->first();
    }

    /**
     * Get all active staff account
     *
     * @return mixed
     */
    public function fetchAllStaff()
    {
        return User::whereAccountType(User::STAFF)->whereState(User::ACTIVE)->get();
    }

    /**
     * Fetch all user
     *
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return User::whereAccountType(User::STAFF)->get();
    }

    /**
     * Add field for insert
     *
     * @param string $attribute
     * @param string|int $value
     * @param array $fields
     * @return array
     */
    public function addToInsert($attribute, $value, $fields)
    {
        $fields[$attribute] = $value;
        return $fields;
    }

    /**
     * Create new user
     *
     * @param array $fields
     * @param int $createLog
     * @return User
     */
    public function create($fields, $createLog = 1)
    {
        $isCreateLog = 1;
        $userId = User::query()->insertGetId($fields);
        $user = User::find($userId);

        $createLog !== $isCreateLog ?: $this->createLog($user, User::class);

        return $user;
    }

    /**
     * Format a date to insert to db
     *
     * @param string $date
     * @return string
     */
    public function formatDate($date)
    {
        $dob = explode('/', $date);

        $dob = Carbon::create((int)$dob[2], (int)$dob[1], (int)$dob[0]);
        return $dob->format('Y-m-d');
    }

    /**
     * Destroy a user
     *
     * @param User $user
     * @return bool
     * @throws Exception
     */
    public function destroy(User $user)
    {
        if ($user->delete()) {
            if ($user->avatar) {
                $user->deleteAvatarFile();
            }

            $this->deleteLog($user, User::class);

            return true;
        }
        return false;
    }
}
