<?php

namespace App\Providers;

use App\Film;
use App\Repositories\Interfaces\FilmInterface;
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
    }
}
