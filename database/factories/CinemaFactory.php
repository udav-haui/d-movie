<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Cinema;
use Faker\Generator as Faker;

$factory->define(Cinema::class, function (Faker $faker) {
    return [
        Cinema::NAME => $faker->name,
        Cinema::STATUS => rand(0, 1),
        Cinema::ADDRESS => $faker->text(50),
        Cinema::PROVINCE => $faker->name,
        Cinema::PHONE => $faker->phoneNumber,
        Cinema::DESCRIPTION => $faker->text(500)
    ];
});
