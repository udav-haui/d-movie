<?php

namespace App\Repositories\Interfaces;

use App\SocialAccount;
use Laravel\Socialite\Contracts\User as ProviderUser;

/**
 * Class SocialFacebookAccountService
 *
 * @package App\Services
 */
interface SocialAccountRepositoryInterface
{
    /**
     * Get or create new user
     *
     * @param ProviderUser $providerUser
     * @param string $provider
     * @param int $accountType
     * @param int $state
     * @return mixed
     */
    public function createOrGetUser(ProviderUser $providerUser, $provider, $accountType, $state);

    /**
     * Get a exist social account
     *
     * @param string $provider
     * @param int $providerUserId
     * @return SocialAccount
     */
    public function getSocialAccount($provider, $providerUserId);
}
