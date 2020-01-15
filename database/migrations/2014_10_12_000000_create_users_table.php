<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('account_type');
            $table->string('username')->unique()->index()->nullable(true);
            $table->string('name')->nullable(true);
            $table->string('email')->nullable(true)->unique();
            $table->string('password');
            $table->string('phone')->nullable(true);
            $table->string('address')->nullable(true);
            $table->text('avatar')->nullable(true);
            $table->date('dob')->nullable(true);
            $table->smallInteger('state');
            $table->smallInteger('role_id')->unsigned()->index()->nullable(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
