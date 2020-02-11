<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
Route::group(['middleware' => ['locale','prefix' ,'first.use']], function () {
    $admin = \App\Helper\Data::getAdminPath();

    Route::get('/switch-language/{lang}', 'LanguageController@switch')
        ->name('switch-language');
    Route::get('/', 'Frontend\HomeController@index')->name('frontend.dashboard');

    Route::group([
        'namespace' => 'Adminhtml',
        'prefix' => $admin
    ], function () {
        Route::group(['middleware' => 'auth'], function () {
            Route::get('/', 'DashboardController@index');
            /**
             * USER SECTION
             */
            Route::get('users/getActiveUsers', 'Auth\UserController@getActiveUsers')->name('users.getActiveUsers');
            Route::get('users/getUsers', 'Auth\UserController@findUserByNameOrMailOrUsername')->name('users.getUsers');
            Route::put('users/manageUpdate/{user}', 'Auth\UserController@manageUpdate')->name('users.manageUpdate');
            Route::post('users/changeState', 'Auth\UserController@changeState')->name('users.changeState');
            Route::put('users/mass-update', 'Auth\UserController@massUpdate')->name('users.massUpdate');
            Route::put('users/{user}/mass-update', 'Auth\UserController@massSingleUserUpdate')
                ->name('users.massSingleUserUpdate');
            Route::post('users/multi-change-status', 'Auth\UserCOntroller@multiChangeStatus')
                ->name('users.multiChangeStatus');
            Route::delete('users/multi-destroy', 'Auth\UserController@multiDestroy')->name('users.multiDestroy');
            Route::resource('users', 'Auth\UserController');
            /**
             * ROLE SECTION
             */
            Route::get('roles/fetch', 'RoleController@fetch')->name('roles.getRoles');
            Route::get('roles/{role}/get', 'RoleController@get')->name('roles.getRole');
            Route::post('roles/assign', 'RoleController@doAssign')->name('roles.doAssign');
            Route::get('roles/assign', 'RoleController@showAssignForm')->name('roles.assignForm');
            Route::post('roles/singAssign', 'RoleController@doSingleAssign')->name('roles.doSingAssign');
            Route::post('roles/{role}/mass-assign', 'RoleController@massAssign')->name('roles.massAssign');
            Route::resource('roles', 'RoleController');
            /**
             * Api method
             */
            Route::put('user-change-avatar/{user}', 'Auth\UserController@setAvatar')->name('users.setAvatar');
            Route::post('user-change-password/{user}', 'Auth\UserController@changePassword')
                ->name('users.changePassword');

            /** SLIDER SECTION */
            Route::post('sliders/changeStatus/{slider}', 'SliderController@changeStatus')->name('sliders.changeStatus');
            // Route::get('sliders/{slider}/test', 'SliderController@test');
            Route::get('sliders/ajax-index', 'SliderController@ajaxIndex')->name('sliders.ajaxIndex');
            Route::delete('sliders/multi-destroy', 'SliderController@multiDestroy')->name('sliders.multiDestroy');
            Route::post('sliders/multi-change-status', 'SliderController@multiChangeStatus')
                ->name('slider.multiChangeStatus');
            Route::resource('sliders', 'SliderController');

            /** FILM SECTION */

            Route::delete('films/mass/destroy', 'FilmController@massDestroy')
                ->name('films.massDestroy');
            Route::put('films/mass/update', 'FilmController@massUpdate')
                ->name('films.massUpdate');
            Route::resource('films', 'FilmController');

             /** Cinema Section */

            Route::delete('cinemas/mass/destroy', 'CinemaController@massDestroy')
                ->name('cinemas.massDestroy');
            Route::put('cinemas/mass/update', 'CinemaController@massUpdate')
                ->name('cinemas.massUpdate');
            Route::resource('cinemas', 'CinemaController');
        });
//        Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
//        Route::post('login', 'Auth\LoginController@login');
//        Route::post('logout', 'Auth\LoginController@logout')->name('logout');
//        Route::post('password/reset', 'Auth\ResetPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('callback/{provider}', 'SocialAuthController@callback');
        Route::get('redirect/{provider}', 'SocialAuthController@redirect');

        Auth::routes();

        /**
         * MoMo testing
         */
        Route::post('test', 'RoleController@momoTest')->name('momo.testing');
        Route::get('momo/callback', 'RoleController@momoCallback')->name('momo.callback');
    });
});
//
//Route::get('/get-user', 'Adminhtml\RoleController@test_get');
