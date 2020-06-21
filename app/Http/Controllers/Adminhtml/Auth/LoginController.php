<?php

namespace App\Http\Controllers\Adminhtml\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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

    use AuthenticatesUsers {
        login as protected parent_login;
    }

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
     * @inheritDoc
     */
    public function login(Request $request)
    {
        if ($request->isJson() &&
            $request->is('api/*')
        ) {
            $validator = $this->validateLogin($request);
            if ($validator instanceof \Illuminate\Http\JsonResponse) {
                return $validator;
            }

            if ($this->attemptLogin($request)) {
                $user = $this->guard()->user();
                $user->generateToken();
                return response()->json([
                    'data' => $user->toArray(),
                ]);
            }

            return $this->sendFailedLoginResponse($request);
        }
        return $this->parent_login($request);
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
        return view('admin.auth.login');
    }

    /**
     * Custom validate login
     *
     * @param Request $request
     * @return bool|\Illuminate\Http\JsonResponse|void
     */
    protected function validateLogin(Request $request)
    {
        if ($request->isJson()) {
            $validator = Validator::make(
                $request->all(),
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
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 400);
            }
            return true;
        }
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
     * @param  \App\User  $user
     * @return mixed|void
     */
    protected function authenticated(Request $request, $user)
    {
        $user->generateToken();
        if (!$user->isActive()) {
            $this->guard()->logout();
            $user->clearApiToken();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            /**
             * Nếu trạng thái của user là chưa được xác thực thì chạy vào hàm
             */
            return $user->getStatus() === \App\User::NOT_VERIFY_BY_ADMIN ?
                ($this->loggedOut($request) ?: back()->with('error', __('Your account has not been activated.'))
                    ->withInput()) :
                ($this->loggedOut($request) ?: back()->with('error', __('Your account has been deactivate.'))
                    ->withInput());
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

        return $this->loggedOut($request) ?: redirect(route('login'));
    }
}
