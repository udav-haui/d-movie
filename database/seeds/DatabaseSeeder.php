<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(UsersTableSeeder::class);
//        $this->call(SliderSeeder::class);

        \App\User::create([
            \App\User::ACCOUNT_TYPE => 0,
            \App\User::CAN_CHANGE_USERNAME => 0,
            \App\User::LOGIN_WITH_SOCIAL_ACCOUNT => 0,
            \App\User::USERNAME => 'root',
            \App\User::NAME => 'Root',
            \App\User::EMAIL => 'admin@dgroup.vn',
            \App\User::PASSWORD => bcrypt('vadu@123'),
            \App\User::GENDER => 1,
            \App\User::PHONE => '0327811555',
            \App\User::STATE => 1
        ]);
    }
}
