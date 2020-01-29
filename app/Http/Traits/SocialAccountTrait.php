<?php


namespace App\Http\Traits;

use App\Repositories\SocialAccountRepository;
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
     * @var SocialAccountRepository
     */
    private $socialAccountRepository;

    /**
     * Get or create new user
     *
     * @param ProviderUser $providerUser
     * @param string $provider
     * @return mixed
     */
    public function createOrGetUser(ProviderUser $providerUser, $provider)
    {
        $user = $this->socialAccountRepository->createOrGetUser(
            $providerUser,
            $provider,
            $this->accountType,
            $this->state
        );
        return $user;
    }
}
