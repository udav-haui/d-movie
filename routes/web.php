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
            Route::get('user/getUsers', 'Auth\UserController@getUsers')->name('user.getUsers');
            Route::resource('user', 'Auth\UserController');
            /**
             * ROLE SESSION
             */
            Route::post('roles/assign', 'RoleController@doAssign')->name('roles.doAssign');
            Route::get('roles/assign', 'RoleController@showAssignForm')->name('roles.assignForm');
            Route::resource('roles', 'RoleController');
            /**
             * Api method
             */
            Route::put('user-change-avatar/{user}', 'Auth\UserController@setAvatar')->name('user.setAvatar');
            Route::post('user-change-password/{user}', 'Auth\UserController@changePassword')
                ->name('user.changePassword');
        });
        Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
        Route::post('login', 'Auth\LoginController@login');
        Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    });
});
Route::get('/callback/{provider}', 'SocialAuthController@callback');
Route::get('/redirect/{provider}', 'SocialAuthController@redirect');
