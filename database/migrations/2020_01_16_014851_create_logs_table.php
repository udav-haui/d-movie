<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('id')->comment('Log\'s Identifier');
            $table->string('short_message')->comment('Log\'s short message');
            $table->json('message')->comment('Log\'s Message');
            $table->string('action')->comment('Log\'s Action');
            $table->string('target_model')->comment('Type of model be changed');
            $table->integer('target_id')->comment('Changed target');
            $table->unsignedInteger('user_id')->index()->comment('Create by user');
            $table->timestamp('created_at')->useCurrent()->comment('Create time');
            $table->timestamp('updated_at')->useCurrent()->comment('Update time');

            $table->foreign('user_id')->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
