<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Show;
use Faker\Generator as Faker;

$factory->define(Show::class, function (Faker $faker) {
    return [
        Show::NAME => $faker->unique()->name,
        Show::STATUS => rand(0, 1),
        Show::CINEMA_ID => rand(3, 4)
    ];
});
