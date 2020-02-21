<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->increments('id')->comment('Seat\'s Identifier');
            $table->tinyInteger('status')->default(0)->comment('Seat\'s Status');
            $table->tinyInteger('type')->default(0)->comment('Seat\'s type');
            $table->char('row', 2)->comment('Seat\'s row');
            $table->unsignedTinyInteger('number')->comment('Seat\'s number');
            $table->unsignedInteger('show_id')->index()->comment('The show which seat belong to.');

            $table->foreign('show_id')->references('id')
                ->on('shows')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seats');
    }
}
