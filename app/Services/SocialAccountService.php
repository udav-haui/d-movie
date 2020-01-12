<?php

namespace App\Services;

use App\SocialAccount;
use App\User;
use Laravel\Socialite\Contracts\User as ProviderUser;

/**
 * Class SocialFacebookAccountService
 *
 * @package App\Services
 */
class SocialAccountService
{
    /**
     * Get or create new user
     *
     * @param ProviderUser $providerUser
     * @param string $provider
     * @return mixed
     */
    public function createOrGetUser(ProviderUser $providerUser, $provider)
    {
        $account = SocialAccount::whereProvider($provider)
            ->whereProviderUserId($providerUser->getId())
            ->first();
        if ($account) {
            return $account->user;
        } else {
            $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => $provider
            ]);
            $user = User::whereEmail($providerUser->getEmail())->first();
            if (!$user) {
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'name' => $providerUser->getName(),
                    'password' => bcrypt(rand(1, 10000)),
                ]);
            }

            $account->user()->associate($user);
            $account->save();
            return $user;
        }
    }
}
