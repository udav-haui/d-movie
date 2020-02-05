<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Slider;
use Faker\Generator as Faker;

$factory->define(Slider::class, function (Faker $faker) {
    return [
        'status' => rand(0, 1),
        'title' => $faker->text(100),
        'image' => null,
        'href' => $faker->url,
        'order' => rand(0, 1000)
    ];
});
