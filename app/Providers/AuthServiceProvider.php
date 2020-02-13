<?php

namespace App\Providers;

use App\Policies\CinemaPolicy;
use App\Policies\FilmPolicy;
use App\Policies\RolePolicy;
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
