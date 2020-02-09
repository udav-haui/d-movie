<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Film;
use Faker\Generator as Faker;

$factory->define(Film::class, function (Faker $faker) {
    return [
        Film::TITLE => $faker->text(150),
        Film::POSTER => $faker->text(70),
        Film::DIRECTOR => $faker->name,
        Film::CAST => $faker->name,
        Film::GENRE => $faker->name,
        Film::RUNNING_TIME => rand(120, 360),
        Film::LANGUAGE => 'English',
        Film::DESCRIPTION => $faker->sentence(50),
        Film::RELEASE_DATE => $faker->date('Y-m-d', 'now'),
        Film::MARK => $faker->randomElement(['p', 'c13', 'c16', 'c18']),
        Film::TRAILER => $faker->url
    ];
});
