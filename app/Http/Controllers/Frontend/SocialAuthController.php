<?php

namespace App\Http\Controllers\Frontend;

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
        $this->accountType = \App\User::CUSTOMER;
        $this->state = \App\User::ACTIVE;
    }

    /**
     * Set callback url for provider
     *
     * @param string $provider
     * @return string
     */
    public function setRedirectUrl($provider)
    {
        return 'https://dmovie.vn/callback/' . $provider;
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
        try {
            if (request()->has('error')) {
                return redirect(route('frontend.login', ['action' => 'login']));
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
                return redirect(route('frontend.login', ['action' => 'login']))
                    ->with('error', __('Your social account was linked by a staff account and not be verified.'));
            }
            if ($user->isActive()) {
                auth()->login($user, true);
                return redirect(route('frontend.home'));
            }
            return redirect(route('frontend.login', ['action' => 'login']))
                ->with('error', __('Your account has not been activated.'));
        } catch (\Exception $e) {
            return redirect(route('frontend.login', ['action' => 'login']))
                ->with('error', $e->getMessage());
        }
    }
}
