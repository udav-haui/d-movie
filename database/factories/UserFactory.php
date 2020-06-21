<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'account_type' => rand(1,2),
        'can_change_username' => 1,
        'login_with_social_account' => 0,
        'username' => $faker->unique()->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => \Illuminate\Support\Facades\Hash::make('vadu@123'),
        'state' => 1,
        'remember_token' => Str::random(10),
        'created_at' => new DateTime,
        'updated_at' => new DateTime
    ];
});
