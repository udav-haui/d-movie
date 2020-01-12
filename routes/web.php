<?php

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    \Session::put('prefix', request()->route()->getPrefix());

    return view('welcome');
});
Route::group(['middleware' => 'locale'], function () {
    Route::get('/switch-language/{lang}', 'LanguageController@switch')
        ->name('switch-language');

    Route::group([
        'namespace' => 'Adminhtml',
        'prefix' => 'admin'
    ], function () {
        Route::get('/', 'DashboardController@index');
        Auth::routes();
    });
});
Route::get('/callback/{provider}', 'SocialAuthController@callback');
Route::get('/redirect/{provider}', 'SocialAuthController@redirect');
