<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateFilmSchedulesTable
 */
class CreateFilmSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('film_show', function (Blueprint $table) {
            $table->increments('id')->comment('Schedule Identifier');
            $table->tinyInteger('status')->default(0)->comment('Schedule Status');
            $table->date('start_date')->useCurrent()->comment('Schedule start date');
            $table->unsignedInteger('film_id')->index()->comment('Film to show');
            $table->unsignedInteger('show_id')->index()->comment('Show to show');

            $table->timestamp('created_at')->useCurrent()->comment('Create time');
            $table->timestamp('updated_at')->useCurrent()->comment('Modify time');

            $table->foreign('film_id')->references('id')
                ->on('films')
                ->onUpdate('cascade')
                ->onDelete('cascade');

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
        Schema::dropIfExists('film_show');
    }
}
