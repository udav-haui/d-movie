<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Contact;
use Faker\Generator as Faker;

$factory->define(Contact::class, function (Faker $faker) {
    return [
        Contact::STATUS => rand(0, 1),
        Contact::CINEMA => 1,
        Contact::CONTACT_NAME => $faker->name,
        Contact::CONTACT_EMAIL => $faker->email,
        Contact::CONTACT_PHONE => $faker->phoneNumber,
        Contact::CONTACT_CONTENT => $faker->paragraph
    ];
});
