<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
            $table->increments('id')->comment('User\'s Identifier.');
            $table->tinyInteger('account_type')->comment('User group.');
            $table->tinyInteger('can_change_username')->default(0)->comment('Can user change username');
            $table->tinyInteger('login_with_social_account')->default(0)->comment('Is user login with social account');
            $table->string('username')->nullable()->unique()->index()->comment('Username of user\'s account.');
            $table->string('name')->nullable()->comment('User\'s Name');
            $table->string('email')->unique()->comment('User\'s Email.');
            $table->string('password')->comment('User\'s Password');
            $table->tinyInteger('gender')->nullable()->comment('Gender of user');
            $table->char('phone', 12)->nullable()->comment('User\'s Phone number');
            $table->string('address')->nullable()->comment('User\'s Address');
            $table->text('avatar')->nullable()->comment('User\'s Avatar');
            $table->date('dob')->nullable()->comment('User\'s Date of birth');
            $table->tinyInteger('state')->comment('User\'s State');
            $table->string('description')->nullable()->comment('User\'s Description');
            $table->unsignedInteger('role_id')->index()->nullable()->comment('User\'s Role');
            $table->rememberToken()->comment('Remember login token');
            $table->timestamp('created_at')->useCurrent()->comment('Create time');
            $table->timestamp('updated_at')->useCurrent()->comment('Update time');

            $table->foreign('role_id')->references('id')
                ->on('roles')
                ->onUpdate('cascade')
                ->onDelete('SET NULL');
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
