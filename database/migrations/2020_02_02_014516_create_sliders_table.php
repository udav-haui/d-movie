<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->increments('id')->comment('Slide Item Identity');
            $table->tinyInteger('status')->default(0)->comment('Item status');
            $table->string('title')->nullable()->comment('Slide item title');
            $table->text('image')->comment('Item image path');
            $table->string('href')->nullable()->comment('Item link');
            $table->unsignedInteger('order')->default(0)->comment('Item order');
            $table->timestamp('created_at')->useCurrent()->comment('Item create time');
            $table->timestamp('updated_at')->useCurrent()->comment('Item update time');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sliders');
    }
}
