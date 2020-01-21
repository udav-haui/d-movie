<?php


namespace App\Http\Traits;


use App\SocialAccount;
use App\User;
use Laravel\Socialite\Contracts\User as ProviderUser;

trait SocialAccountTrait
{
    /**
     * Determine type of account, super admin, staff, customer
     *
     * @var int $accountType
     */
    protected $accountType;

    /**
     * Determine account state by logging with social account
     *
     * @var int $state
     */
    protected $state;

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
                    'account_type' => $this->accountType,
                    'can_change_username' => 1,
                    'login_with_social_account' => 1,
                    'name' => $providerUser->getName(),
                    'password' => bcrypt(rand(1, 10000)),
                    'state' => $this->state
                ]);
            }

            $account->user()->associate($user);
            $account->save();
            return false;
        }
    }
}
