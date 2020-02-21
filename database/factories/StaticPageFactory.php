<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\StaticPage;
use Faker\Generator as Faker;

$factory->define(StaticPage::class, function (Faker $faker) {
    return [
        StaticPage::NAME => $faker->name,
        StaticPage::STATUS => rand(0, 1),
        StaticPage::SLUG => $faker->slug,
        StaticPage::LANGUAGE => array_rand(['vi', 'en']),
        StaticPage::CONTENT => $faker->paragraph
    ];
});
