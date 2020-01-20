<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Services\SocialAccountService;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Create a redirect method to Facebook Api.
     *
     * @param string $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Call back from provider
     *
     * @param SocialAccountService $service
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function callback(SocialAccountService $service, $provider)
    {
        $prefix = Session::get('prefix');
        if (request()->has('error')) {
            return redirect($prefix . '/login');
        }
        $providerUser = Socialite::driver($provider)->stateless()->user();
        $user = $service->createOrGetUser(
            $providerUser,
            $provider,
            $prefix == '/admin' ? \App\User::NORMAL_USER : \App\User::CUSTOMER,
            $prefix == '/admin' ? \App\User::NOT_ACTIVATE : \App\User::ACTIVE
        );
        if (!$user) {
            return redirect('/admin/login')
                ->with('error', __('Please wait until we activate your account, thank you!'));
        } elseif ($user->isActive()) {
            auth()->login($user, true);
            return redirect($prefix . '/');
        }
        return redirect('/admin/login')
            ->with('error', __('Your account has not been activated.'));
    }
}
