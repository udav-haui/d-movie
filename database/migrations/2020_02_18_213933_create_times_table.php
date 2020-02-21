<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateTimesTable
 */
class CreateTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('times', function (Blueprint $table) {
            $table->increments('id')->comment('Time show identifier');
            $table->date('start_date')->comment('Date\'s Time');
            $table->time('start_time')->comment('Start time for associate schedule');
            $table->time('stop_time')->comment('Stop time for associate schedule');
            $table->unsignedInteger('total_time')->comment('Total time for this show time');
            $table->unsignedInteger('film_show_id')->index()->nullable()->comment('Associate schedule');
            $table->foreign('film_show_id')->references('id')
                ->on('film_show')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamp('created_at')->useCurrent()->comment('Create time');
            $table->timestamp('updated_at')->useCurrent()->comment('Modify time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('times');
    }
}
