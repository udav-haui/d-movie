<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Combo;
use Faker\Generator as Faker;

$factory->define(Combo::class, function (Faker $faker) {
    return [
        Combo::STATUS => rand(0, 1),
        Combo::NAME => $faker->name,
        Combo::DESCRIPTION => $faker->text(200),
        Combo::PRICE => rand(60000, 150000)
    ];
});
