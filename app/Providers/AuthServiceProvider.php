<?php

namespace App\Providers;

use App\Policies\CinemaPolicy;
use App\Policies\FilmPolicy;
use App\Policies\RolePolicy;
use App\Policies\SeatPolicy;
use App\Policies\ShowPolicy;
use App\Policies\SliderPolicy;
use App\Policies\UserPolicy;
use App\Role;
use App\Show;
use App\Slider;
use App\User;
use App\Film;
use App\Cinema;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * Class AuthServiceProvider
 *
 * @package App\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Slider::class => SliderPolicy::class,
        Film::class => FilmPolicy::class,
        Cinema::class => CinemaPolicy::class,
        Show::class => ShowPolicy::class,
        \App\Seat::class => SeatPolicy::class,
        \App\FilmSchedule::class => \App\Policies\FilmSchedulePolicy::class,
        \App\Contact::class => \App\Policies\ContactPolicy::class,
        \App\Customer::class => \App\Policies\CustomerPolicy::class,
        \App\StaticPage::class => \App\Policies\StaticPagePolicy::class,
        \App\Log::class => \App\Policies\LogPolicy::class,
        \App\Combo::class => \App\Policies\ComboPolicy::class,
        \App\Dashboard::class => \App\Policies\DashboardPolicy::class,
        \App\Booking::class => \App\Policies\BookingPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
