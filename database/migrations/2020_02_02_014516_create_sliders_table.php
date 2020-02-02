<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->integerIncrements('id')->comment('Slider Item Identity');
            $table->unsignedSmallInteger('status')->default(0)->comment('Item status');
            $table->text('image')->nullable(true)->comment('Item image path');
            $table->string('href')->nullable(true)->comment('Item link');
            $table->unsignedInteger('order')->nullable(true)->comment('Item order');
            $table->timestamp('created_at')->useCurrent()->comment('Item created time');
            $table->timestamp('updated_at')->useCurrent()->comment('Item updated time');;
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
