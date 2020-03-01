<?php

namespace App\Http\Controllers\Adminhtml;

use App\Http\Controllers\Controller;
use App\Http\Traits\SocialAccountTrait;
use App\Repositories\Interfaces\SocialAccountRepositoryInterface;
use Socialite;

class SocialAuthController extends Controller
{
    use SocialAccountTrait;

    /**
     * SocialAuthController constructor.
     *
     * @param SocialAccountRepositoryInterface $socialAccountRepository
     */
    public function __construct(
        SocialAccountRepositoryInterface $socialAccountRepository
    ) {
        $this->socialAccountRepository = $socialAccountRepository;
        $this->accountType = \App\User::STAFF;
        $this->state = \App\User::NOT_VERIFY_BY_ADMIN;
    }

    /**
     * Set callback url for provider
     *
     * @param string $provider
     * @return string
     */
    public function setRedirectUrl($provider)
    {
        return 'https://dmovie.vn/admin/callback/' . $provider;
    }

    /**
     * Get redirect
     *
     * @param string $provider
     * @return $this
     */
    public function redirect($provider)
    {
        return Socialite::driver($provider)
            ->redirectUrl($this->setRedirectUrl($provider))
            ->redirect();
    }

    /**
     * Get callback
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function callback($provider)
    {
        if (request()->has('error')) {
            return redirect(\App\Helper\Data::ADMIN_LOGIN_PATH);
        }
        $providerUser = Socialite::driver($provider)
            ->redirectUrl($this->setRedirectUrl($provider))
            ->stateless()->user();
        /** @var \App\User $user */
        $user = $this->createOrGetUser(
            $providerUser,
            $provider
        );
        if ($user->isNotVerified()) {
            return redirect(\App\Helper\Data::ADMIN_LOGIN_PATH)
                ->with('error', __('Please wait until we activate your account, thank you!'));
        } elseif ($user->isActive()) {
            auth()->login($user, true);
            return redirect(\App\Helper\Data::ADMIN_PATH);
        }
        return redirect(\App\Helper\Data::ADMIN_LOGIN_PATH)
            ->with('error', __('Your account has not been activated.'));
    }
}
