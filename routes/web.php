<?php
$admin = \App\Helper\Data::getAdminPath();

Route::get('switch-language/{lang}', 'LanguageController@switch')
    ->name('app.switch-language');

Route::group(['namespace' => 'Frontend'], function () {
    Route::get('/', function () {
        return redirect(route('frontend.home'));
    });
    Route::get('home.html', 'HomeController@index')->name('frontend.home');
    Route::get('auth/{action}.html', 'LoginController@getLoginForm')
        ->name('frontend.login');
    Route::post('login.html', 'LoginController@login')
        ->name('frontend.doLogin');
    Route::post('logout.html', 'LoginController@logout')
        ->name('frontend.logout');
    Route::get('callback/{provider}', 'SocialAuthController@callback')
        ->name('fe.LoginWith');
    Route::get('redirect/{provider}', 'SocialAuthController@redirect')
        ->name('fe.getLoginWith');
    Route::post('member/register', 'RegisterController@register')
        ->name('member.register');

    /** CUSTOMER */

    Route::post('send-feedback', 'StaticPageController@sendFeedback')
        ->name('customer.sendFeedback');
    Route::get(__('member').'/{slug}.html', 'MemberController@show')
        ->name('member.show');
    Route::post('member/{member}/change/password', 'MemberController@changePassword')
        ->name('member.changePassword');

    Route::post('member/{member}/change/information', 'MemberController@changeInformation')
        ->name('member.changeInformation');

    /** STATIC PAGE */

    Route::get(__('informations').'/{pageSlug}.html', 'StaticPageController@show')->name('fe.showStaticPage');

    /** CINEMA */

    Route::get('cinemas/get-active', 'CinemaController@getList')
        ->name('fe.cinemas.getCinemas');

    /** FILM */

    Route::get('films/get/{film}', 'FilmController@get')
        ->name('films.api.getFilm');
    Route::get(__('film-detail').'/{film}-{slug}.html', 'FilmController@show')
        ->name('fe.filmDetail');

    /** BOOKING */
    Route::get(__('booking').'.html', 'BookingController@selectSeats')
        ->name('bookings.selectSeats');
    Route::post('bookings/get/payment', 'BookingController@getPayment')
        ->name('bookings.api.getPayment');
    Route::get(__('booking').'/{slug}', 'BookingController@showResult')
        ->name('bookings.result');

    Route::post('bookings/select-seat', 'BookingController@selectSeat')
        ->name('bookings.api.selectSeat');
    Route::post('bookings/send-selected-seats', 'BookingController@sendSelectedSeats')
        ->name('bookings.api.sendSelectedSeatsToJoiner');

    /** SCHEDULE */
    Route::get('movie-schedule.html', 'ScheduleController@index')
        ->name('schedule.index');

    /** PAYMENT CALLBACK */
    Route::get('/payment/callback', 'BookingController@callback')
        ->name('payment.callback');
});

Route::group([
    'namespace' => 'Adminhtml',
    'prefix' => $admin
], function () {
    Route::group(['middleware' => ['auth', 'is.admin']], function () {

        Route::get('/', function () {
            return redirect(route('dashboard'));
        });
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
        /**
         * USER SECTION
         */
        Route::get('users/getActiveUsers', 'Auth\UserController@getActiveUsers')
            ->name('users.getActiveUsers');
        Route::get('users/getUsers', 'Auth\UserController@findUserByNameOrMailOrUsername')
            ->name('users.getUsers');
        Route::get('users/get-users-by-ids', 'Auth\UserController@getUsersByIds')
            ->name('users.getUsersByIds');
        Route::put('users/manageUpdate/{user}', 'Auth\UserController@manageUpdate')->name('users.manageUpdate');
        Route::post('users/changeState', 'Auth\UserController@changeState')->name('users.changeState');
        Route::put('users/mass-update', 'Auth\UserController@massUpdate')->name('users.massUpdate');
        Route::put('users/{user}/mass-update', 'Auth\UserController@massSingleUserUpdate')
            ->name('users.massSingleUserUpdate');
        Route::post('users/multi-change-status', 'Auth\UserCOntroller@multiChangeStatus')
            ->name('users.multiChangeStatus');
        Route::delete('users/multi-destroy', 'Auth\UserController@multiDestroy')->name('users.multiDestroy');
        Route::get(__('my-profile') . '.html', 'Auth\UserController@getProfile')->name('users.getProfile');
        Route::resource('users', 'Auth\UserController');

        /** CUSTOMER SECTION */

        Route::get('customers', 'Auth\UserController@customerIndex')
            ->name('users.customer.index');
        Route::get('customers/create', 'Auth\UserController@customerCreate')
            ->name('users.customer.create');
        Route::post('customers/store', 'Auth\UserController@customerStore')
            ->name('users.customer.store');
        Route::get('customers/{customer}/edit', 'Auth\UserController@customerEdit')
            ->name('users.customer.edit');
        Route::put('customers/{customer}/edit', 'Auth\UserController@customerUpdate')
            ->name('users.customer.update');

        /** BOOKING SECTION */
        Route::get('bookings/'. __('print-ticket') . '-{ticket}.html', 'BookingController@printTicket')
            ->name('bookings.printTicket');
        Route::resource('bookings', 'BookingController');

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
        Route::get('films/attempt-select2', 'FilmController@attemptSelect2')
            ->name('films.attemptSelect2');
        Route::get('films/get/{film}', 'FilmController@getFilm')
            ->name('films.getFilm');
        Route::resource('films', 'FilmController');

        /** Cinema Section */

        Route::delete('cinemas/mass/destroy', 'CinemaController@massDestroy')
            ->name('cinemas.massDestroy');
        Route::put('cinemas/mass/update', 'CinemaController@massUpdate')
            ->name('cinemas.massUpdate');
        Route::get('cinemas/{cinema}/get-shows', 'CinemaController@getShows')
            ->name('cinemas.getShows');

        Route::get('cinemas/attempt-select2', 'CinemaController@attemptSelect2')
            ->name('cinemas.attemptSelect2');
        Route::get('cinemas/get/{cinema}', 'CinemaController@get')
            ->name('cinemas.getCinema');

        Route::resource('cinemas', 'CinemaController');

        /** Show Section */
        Route::get('shows/create/{cinemaId}', 'ShowController@createWithCinema')
            ->name('shows.createWithCinema');

        Route::delete('shows/mass/destroy', 'ShowController@massDestroy')
            ->name('shows.massDestroy');
        Route::put('shows/mass/update', 'ShowController@massUpdate')
            ->name('shows.massUpdate');

        Route::get('shows/get/seats/by/{show}', 'ShowController@getSeats')
            ->name('shows.getSeats');
        Route::get('shows/create/seats/by/{show}', 'ShowController@createSeats')
            ->name('shows.createSeats');
        Route::get('shows/attempt-select2', 'ShowController@attemptSelect2')
            ->name('shows.attemptSelect2');
        Route::get('shows/get/{show}', 'ShowController@getShow')
            ->name('shows.getShow');
        Route::resource('shows', 'ShowController');


        /** SEAT SECTION */
        Route::delete('seats/mass/destroy', 'SeatController@massDestroy')
            ->name('seats.massDestroy');
        Route::put('seats/mass/update', 'SeatController@massUpdate')
            ->name('seats.massUpdate');
        Route::resource('seats', 'SeatController');

        /** FILM SCHEDULE */
        Route::delete('fs/mass/destroy', 'FilmScheduleController@massDestroy')
            ->name('fs.massDestroy');
        Route::put('fs/mass/update', 'FilmScheduleController@massUpdate')
            ->name('fs.massUpdate');
        Route::get('fs/get/showtime/{schedule}', 'FilmScheduleController@getShowtime')->name('fs.getShowtime');
        Route::resource('fs', 'FilmScheduleController');

        /** TIMES */

        Route::get('fs/times/create/by/{schedule}', 'TimeController@createTimeBySchedule')
            ->name('times.createTimeBySchedule');
        Route::delete('fs/times/mass/destroy', 'TimeController@massDestroy')
            ->name('times.massDestroy');
        Route::resource('fs/times', 'TimeController');


        /** CONTACTS */
        Route::delete('contacts/mass/destroy', 'ContactController@massDestroy')
            ->name('contacts.massDestroy');
        Route::put('contacts/mass/update', 'ContactController@massUpdate')
            ->name('contacts.massUpdate');
        Route::resource('contacts', 'ContactController');


        /** STATIC PAGE */

        Route::delete('pages/mass/destroy', 'StaticPageController@massDestroy')
            ->name('static-pages.massDestroy');
        Route::put('pages/mass/update', 'StaticPageController@massUpdate')
            ->name('static-pages.massUpdate');
        Route::resource('static-pages', 'StaticPageController');



        /** COMBO SECTION */

        Route::delete('combos/mass/destroy', 'ComboController@massDestroy')
            ->name('combos.massDestroy');
        Route::put('combos/mass/update', 'ComboController@massUpdate')
            ->name('combos.massUpdate');
        Route::resource('combos', 'ComboController');

        /** SYSTEM LOGS */

        Route::resource('logs', 'LogController');
    });
    Route::get('callback/{provider}', 'SocialAuthController@callback');
    Route::get('redirect/{provider}', 'SocialAuthController@redirect');
    Auth::routes();

    /**
     * MoMo testing
     */
    Route::post('test', 'RoleController@momoTest')->name('momo.testing');
    Route::get('momo/callback', 'RoleController@momoCallback')->name('momo.callback');
});
//
//Route::get('/get-user', 'Adminhtml\RoleController@test_get');
