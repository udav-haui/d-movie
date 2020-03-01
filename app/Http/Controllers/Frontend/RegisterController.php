<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::FRONTEND_HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            User::NAME => ['required', 'string', 'max:255'],
            User::EMAIL => ['required', 'string', 'email', 'max:255', 'unique:users'],
            User::PASSWORD => ['required', 'string', 'min:8', 'confirmed'],
            User::DOB => ['required', 'date_format:d-m-Y'],
            User::PHONE => [
                'required',
                'regex:/((\+84|84|0)[9|3])+([0-9]{8})\b/i'
            ],
            'g-recaptcha-response' => 'required|recaptcha'
        ];

        $msgs = [
            User::NAME.'.required' => __('The :attribute is required'),
            User::NAME.'.max' => __('You can not input more than :max character'),
            User::NAME.'.min' => __('You can not input less than :min character'),
            User::EMAIL.'.required' => __('You must input :attribute'),
            User::EMAIL.'.max' => __('You can not input more than :max character'),
            User::EMAIL.'.email' => __('You must input a correct :attribute'),
            User::EMAIL.'.unique' => __('This :attribute has been taken'),
            user::PASSWORD.'.required' => __('The :attribute is required'),
            User::PASSWORD.'.min' => __('You can not input less than :min character'),
            User::PASSWORD.'.confirmed' => __('The :attribute does not match.'),
            User::PHONE.'.required' => __('You must input :attribute'),
            User::PHONE.'.regex' => __('Please input a correct Vietnam :attribute'),
            User::DOB.'.required' => __('The :attribute is required'),
            User::DOB.'.date_format' => __('Please input a correct date format (dd-mm-yyyy)'),
            'g-recaptcha-response.required' => __('The :attribute is required'),
            'g-recaptcha-response.recaptcha' => __('Captcha error! Try again later')
        ];

        $attributes = [
            User::NAME => __('name'),
            User::EMAIL => __('Email'),
            User::PHONE => __('Phone'),
            User::DOB => __('Date of birth'),
            User::PASSWORD => __('Password'),
            'g-recaptcha-response' => __('Captcha')
        ];

        return Validator::make($data, $rules, $msgs, $attributes);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            User::DOB => \Carbon\Carbon::make($data[User::DOB])->format('Y-m-d'),
            User::PHONE => $data[User::PHONE],
            User::GENDER => $data[User::GENDER],
            User::ACCOUNT_TYPE => User::CUSTOMER,
            User::STATE => User::ACTIVE
        ]);
    }
}
