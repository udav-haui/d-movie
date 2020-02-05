<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['locale', 'prefix']], function () {
    Route::get('/switch-language/{lang}', 'LanguageController@switch')
        ->name('switch-language');
    Route::get('/', 'Frontend\HomeController@index')->name('frontend.dashboard');

    Route::group([
        'namespace' => 'Adminhtml',
        'prefix' => 'admin'
    ], function () {
        Route::group(['middleware' => 'auth'], function () {
            Route::get('/', 'DashboardController@index');
            /**
             * USER SESSION
             */
            Route::get('users/getActiveUsers', 'Auth\UserController@getActiveUsers')->name('users.getActiveUsers');
            Route::get('users/getUsers', 'Auth\UserController@findUserByNameOrMailOrUsername')->name('users.getUsers');
            Route::put('users/manageUpdate/{user}', 'Auth\UserController@manageUpdate')->name('users.manageUpdate');
            Route::post('users/changeState', 'Auth\UserController@changeState')->name('users.changeState');
            Route::resource('users', 'Auth\UserController');
            /**
             * ROLE SESSION
             */
            Route::get('roles/fetch', 'RoleController@fetch')->name('roles.getRoles');
            Route::get('roles/{role}/get', 'RoleController@get')->name('roles.getRole');
            Route::post('roles/assign', 'RoleController@doAssign')->name('roles.doAssign');
            Route::get('roles/assign', 'RoleController@showAssignForm')->name('roles.assignForm');
            Route::post('roles/singAssign', 'RoleController@doSingleAssign')->name('roles.doSingAssign');
            Route::resource('roles', 'RoleController');
            /**
             * Api method
             */
            Route::put('user-change-avatar/{user}', 'Auth\UserController@setAvatar')->name('users.setAvatar');
            Route::post('user-change-password/{user}', 'Auth\UserController@changePassword')
                ->name('users.changePassword');

            /** SLIDER SESSION */
            Route::post('sliders/changeStatus/{slider}', 'SliderController@changeStatus')->name('sliders.changeStatus');
            // Route::get('sliders/{slider}/test', 'SliderController@test');
            Route::get('sliders/ajaxIndex', 'SliderController@ajaxIndex')->name('sliders.ajaxIndex');
            Route::resource('sliders', 'SliderController');
        });
        Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
        Route::post('login', 'Auth\LoginController@login');
        Route::post('logout', 'Auth\LoginController@logout')->name('logout');
        Route::get('callback/{provider}', 'SocialAuthController@callback');
        Route::get('redirect/{provider}', 'SocialAuthController@redirect');

        /**
         * MoMo testing
         */
        Route::post('test', 'RoleController@momoTest')->name('momo.testing');
        Route::get('momo/callback', 'RoleController@momoCallback')->name('momo.callback');
    });
});
//
//Route::get('/get-user', 'Adminhtml\RoleController@test_get');
