<?php

namespace App\Repositories;

use App\Repositories\Interfaces\SocialAccountRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\SocialAccount;
use Laravel\Socialite\Contracts\User as ProviderUser;

/**
 * Class SocialFacebookAccountService
 *
 * @package App\Services
 */
class SocialAccountRepository implements SocialAccountRepositoryInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * SocialAccountRepository constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * Get or create new user
     *
     * @param ProviderUser $providerUser
     * @param string $provider
     * @param int $accountType
     * @param int $state
     * @return \App\User|bool
     */
    public function createOrGetUser(
        ProviderUser $providerUser,
        $provider,
        $accountType,
        $state
    ) {
        $account = $this->getSocialAccount($provider, $providerUser->getId());
        if ($account) {
            return $account->user;
        } else {
            $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => $provider
            ]);

            /** @var string $providerUserEmail */
            $providerUserEmail = $providerUser->getEmail();

            $user = ($this->userRepository->findUserByEmail($providerUserEmail))->first();

            if (!$user) {

                /** @var string $providerName */
                $providerName = $providerUser->getName();

                /** @var array $insertFields */
                $insertFields = [];

                $insertFields = $this->userRepository
                    ->addToInsert('email', $providerUserEmail, $insertFields);
                $insertFields = $this->userRepository
                    ->addToInsert(
                        'account_type',
                        $accountType,
                        $insertFields
                    );
                $insertFields = $this->userRepository
                    ->addToInsert(
                        'can_change_username',
                        \App\User::CAN_CHANGE_USERNAME,
                        $insertFields
                    );
                $insertFields = $this->userRepository
                    ->addToInsert(
                        'login_with_social_account',
                        \App\User::FIRST_LOGIN_WITH_SOCIAL_ACCOUNT,
                        $insertFields
                    );
                $insertFields = $this->userRepository
                    ->addToInsert(
                        'name',
                        $providerName,
                        $insertFields
                    );
                $insertFields = $this->userRepository
                    ->addToInsert(
                        'password',
                        bcrypt(rand(1, 10000)),
                        $insertFields
                    );
                $insertFields = $this->userRepository
                    ->addToInsert(
                        'state',
                        $state,
                        $insertFields
                    );

                $user = $this->userRepository->create($insertFields);
            }

            $account->user()->associate($user);
            $account->save();
            return false;
        }
    }

    /**
     * Get a exist social account
     *
     * @param string $provider
     * @param int $providerUserId
     * @return SocialAccount
     */
    public function getSocialAccount($provider, $providerUserId)
    {
        return SocialAccount::whereProvider($provider)
            ->whereProviderUserId($providerUserId)
            ->first();
    }
}
