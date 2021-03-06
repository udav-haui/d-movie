<?php

namespace App\Providers;

use App\Repositories\BookingRepository;
use App\Repositories\CinemaRepository;
use App\Repositories\ContactRepository;
use App\Repositories\FilmRepository;
use App\Repositories\FilmScheduleRepository;
use App\Repositories\Interfaces\BookingRepositoryInterface;
use App\Repositories\Interfaces\CinemaRepositoryInterface;
use App\Repositories\Interfaces\ContactRepositoryInterface;
use App\Repositories\Interfaces\FilmRepositoryInterface;
use App\Repositories\Interfaces\FilmScheduleRepositoryInterface;
use App\Repositories\Interfaces\LogRepositoryInterface;
use App\Repositories\Interfaces\MemberRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\SeatRepositoryInterface;
use App\Repositories\Interfaces\ShowRepositoryInterface;
use App\Repositories\Interfaces\SliderRepositoryInterface;
use App\Repositories\Interfaces\SocialAccountRepositoryInterface;
use App\Repositories\Interfaces\StaticPageRepositoryInterface;
use App\Repositories\Interfaces\StoreConfigRepositoryInterface;
use App\Repositories\Interfaces\TimeRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\LogRepository;
use App\Repositories\MemberRepository;
use App\Repositories\RoleRepository;
use App\Repositories\SeatRepository;
use App\Repositories\ShowRepository;
use App\Repositories\SliderRepository;
use App\Repositories\SocialAccountRepository;
use App\Repositories\StaticPageRepository;
use App\Repositories\StoreConfigRepository;
use App\Repositories\TimeRepository;
use App\Repositories\ComboRepository;
use App\Repositories\Interfaces\ComboRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoriesServiceProvider
 *
 * @package App\Providers
 */
class RepositoriesServiceProvider extends ServiceProvider
{
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
        $this->app->singleton(FilmRepositoryInterface::class, FilmRepository::class);
        $this->app->singleton(CinemaRepositoryInterface::class, CinemaRepository::class);
        $this->app->singleton(ShowRepositoryInterface::class, ShowRepository::class);
        $this->app->singleton(FilmScheduleRepositoryInterface::class, FilmScheduleRepository::class);
        $this->app->singleton(TimeRepositoryInterface::class, TimeRepository::class);
        $this->app->singleton(StaticPageRepositoryInterface::class, StaticPageRepository::class);
        $this->app->singleton(ContactRepositoryInterface::class, ContactRepository::class);
        $this->app->singleton(ComboRepositoryInterface::class, ComboRepository::class);
        $this->app->singleton(SeatRepositoryInterface::class, SeatRepository::class);
        $this->app->singleton(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->singleton(MemberRepositoryInterface::class, MemberRepository::class);
        $this->app->singleton(StoreConfigRepositoryInterface::class, StoreConfigRepository::class);
    }
}
