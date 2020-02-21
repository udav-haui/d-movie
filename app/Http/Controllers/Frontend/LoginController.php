<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

/**
 * Class LoginController
 *
 * @package App\Http\Controllers\Frontend
 */
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
    protected $redirectTo = RouteServiceProvider::FRONTEND_HOME;

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
        $login = request('email');
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
     * @param string $action
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLoginForm(string $action)
    {
        return view('frontend.login', compact('action'));
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
                'g-recaptcha-response' => 'required|recaptcha'
            ],
            [
                $this->username() . '.required' => __('You must input :attribute'),
                'password.required' => __('You must input :attribute'),
                'g-recaptcha-response.required' => __('The :attribute is required'),
                'g-recaptcha-response.recaptcha' => __('Captcha error! Try again later')
            ],
            [
                $this->username() => __($this->username() == 'email' ? 'Email' : 'Username'),
                'password' => __('Password'),
                'g-recaptcha-response' => __('Captcha')
            ]
        );
    }

    /**
     * Send response when login failed
     *
     * @param Request $request
     * @throws ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [__('These credentials do not match our records.')],
        ]);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
         return $request->only($this->username(), 'password');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if (!$user->isActive()) {
            $this->guard()->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return $user->getStatus() === \App\User::NOT_VERIFY_BY_ADMIN ? $this->loggedOut($request) ?: back()
                ->with('error', __('Your account has not been activated.'))->withInput() :
                $this->loggedOut($request) ?: back()
                    ->with('error', __('Your account has been deactivate.'))->withInput();
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect(route('frontend.home'));
    }
}
