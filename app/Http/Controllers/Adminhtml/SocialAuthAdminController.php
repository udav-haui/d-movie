<?php

namespace App\Http\Controllers\Adminhtml;

use App\Http\Controllers\Controller;
use App\Http\Traits\SocialAuth;
use App\Services\SocialAccountService;

class SocialAuthAdminController extends Controller
{
    use SocialAuth;

    /**
     * Get redirect
     *
     * @param string $provider
     */
    public function redirect($provider)
    {
        $this->redirect($provider);
    }

    /**
     * Get callback
     *
     * @param SocialAccountService $service
     * @param string $provider
     */
    public function callback(SocialAccountService $service, $provider, $redirectTo)
    {
        $this->callback($service, $provider, $redirectTo);
    }
}
