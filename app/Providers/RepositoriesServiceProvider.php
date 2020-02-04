<?php

namespace App\Providers;

use App\Api\Data\SliderInterface;
use App\Repositories\Interfaces\LogRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\SliderRepositoryInterface;
use App\Repositories\Interfaces\SocialAccountRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\LogRepository;
use App\Repositories\RoleRepository;
use App\Repositories\SliderRepository;
use App\Repositories\SocialAccountRepository;
use App\Repositories\UserRepository;
use App\Slider;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(SocialAccountRepositoryInterface::class, SocialAccountRepository::class);
        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
        $this->app->singleton(LogRepositoryInterface::class, LogRepository::class);
        $this->app->singleton(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->singleton(SliderRepositoryInterface::class, SliderRepository::class);
    }
}
