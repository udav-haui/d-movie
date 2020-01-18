<?php

namespace App\Http\Controllers\Adminhtml\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::ADMIN_PATH;

    /**
     * Login username to be used by the controller.
     *
     * @var string
     */
    protected $username;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
    }

    /**
     * Get user login type
     *
     * @return string
     */
    public function findUsername()
    {
        $login = request('login');
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $login]);
        return $fieldType;
    }

    /**
     * Get login username type
     *
     * @return string
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * Show login page view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        \Session::put('prefix', request()->route()->getPrefix());
        return view('admin.auth.login');
    }

    /**
     * Custom validate login
     *
     * @param Request $request
     */
    protected function validateLogin(Request $request)
    {
        $request->validate(
            [
                $this->username() => 'required',
                'password' => 'required',
            ],
            [
                $this->username() . '.required' => __('You must input :attribute'),
                'password.required' => __('You must input :attribute')
            ],
            [
                $this->username() => __($this->username() == 'email' ? 'Email' : 'Username'),
                'password' => __('Password')
            ]
        );
    }
}
