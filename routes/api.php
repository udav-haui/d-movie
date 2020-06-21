<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$admin = \App\Helper\Data::getAdminPath();

Route::group([
    'namespace' => 'Adminhtml',
    'prefix' => $admin
], function () {
    Route::post('user/register', 'Auth\RegisterController@register')
        ->name('api.user.register');

    Route::post('user/login', 'Auth\LoginController@login')
        ->name('api.user.login');

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('list', 'Auth\UserController@list');
        Route::get('store_config/sales/payment_methods', 'StoreConfigsController@getPaymentMethods')
            ->name('api.config.sales.paymentMethods');
        Route::put('store_config/sales/payment_methods', 'StoreConfigsController@savePaymentMethods')
            ->name('api.config.sales.savePaymentMethods');
    });
});

