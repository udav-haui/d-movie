<?php

namespace App\Repositories;

use App\Http\Requests\UserRequest;
use App\Repositories\Abstracts\CRUDModelAbstract;
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
class UserRepository extends CRUDModelAbstract implements UserRepositoryInterface
{
    use LoggerTrait;

    protected $model = User::class;

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
     * @param string|int|null $userId
     * @param User $user
     * @param array $fields
     * @param bool $isWriteLog
     * @return User
     * @throws Exception
     */
    public function update($userId = null, $user = null, $fields = [], bool $isWriteLog = true, bool $encodeSpecChar = true)
    {
        try {
            if ($userId !== null) {
                /** @var User $user */
                $user = $this->find($userId);
            }

            if (!$user) {
                throw new Exception(__('Can not find this user.'));
            }
            if (array_key_exists(User::AVATAR, $fields)) {
                if ($user->getAvatar()) {
                    $user->deleteAvatarFile();
                }
            }

            if (auth()->user()->getAuthIdentifier() === $user->getAuthIdentifier()) {
                if (auth()->user()->cant('update', User::class)) {
                    if (!$user->canChangeUsername() && array_key_exists(User::USERNAME, $fields)) {
                        unset($fields[User::USERNAME]);
                    } else {
                        $fields[User::CAN_CHANGE_USERNAME] = User::CANT;
                    }
                }
            }

            if (array_key_exists(User::DOB, $fields)) {
                if ($fields[User::DOB]) {
                    $fields[User::DOB] = $this->formatDate($fields[User::DOB]);
                }
            }

            return parent::update(null, $user, $fields);
        } catch (Exception $e) {
            throw new Exception(__('Please try again.') . $e->getMessage());
        }
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
        if (auth()->user()->can('selfUpdate', $user)) {
            try {
                if ($user->avatar) {
                    Storage::delete('/public/' . $user->avatar);
                }
                try {
                    $avtPath = request('avatar')->store('uploads', 'public');
                } catch (Exception $fileException) {
                    throw new Exception(__('We cannot upload your image.'));
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
        if (auth()->user()->can('selfUpdate', $user)) {
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
     * @param array $withTbl
     * @param bool $isVisible
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all($withTbl = [], $isVisible = false)
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
     * @param bool $isWriteLog
     * @return User
     * @throws Exception
     */
    public function create($fields = [], bool $isWriteLog = true)
    {
        if (array_key_exists('create_log', $fields)) {
            $isWriteLog = $fields['create_log'];
            unset($fields['create_log']);
        }

        try {
            return parent::create($fields, $isWriteLog);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Destroy a user
     *
     * @param string|int $userId
     * @param User $user
     * @param bool $isWriteLog
     * @return bool
     * @throws Exception
     */
    public function delete($userId = null, $user = null, bool $isWriteLog = true)
    {
        if ($userId !== null) {
            $user = $this->find($userId);
        }
        if ($user) {
            try {
                if ($user->delete()) {
                    if ($user->getAvatar()) {
                        $user->deleteAvatarFile();
                    }

                    $this->deleteLog($user, User::class);

                    return true;
                }
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
        throw new Exception(__('Please try again.'));
    }
}
