<?php

namespace App\Providers;

use App\Cinema;
use App\Film;
use App\Repositories\Interfaces\CinemaInterface;
use App\Repositories\Interfaces\FilmInterface;
use App\Show;
use App\Repositories\Interfaces\ShowInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class ModelsServiceProvider
 *
 * @package App\Providers
 */
class ModelsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(FilmInterface::class, Film::class);
        $this->app->bind(CinemaInterface::class, Cinema::class);
        $this->app->bind(ShowInterface::class, Show::class);
    }
}
