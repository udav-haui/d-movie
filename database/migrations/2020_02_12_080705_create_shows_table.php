<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateShowsTable
 */
class CreateShowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shows', function (Blueprint $table) {
            $table->increments('id')->comment('Show identifier');
            $table->tinyInteger('status')->default(0)->comment('Show status');
            $table->string('name')->default('Unnamed')->comment('Show name');
            $table->unsignedInteger('cinema_id')->index()->comment('Define the cinema which the show is belong to.');
            $table->timestamp('created_at')->useCurrent()->comment('Create time');
            $table->timestamp('updated_at')->useCurrent()->comment('Update time');


            $table->foreign('cinema_id')->references('id')
                ->on('cinemas')
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
        Schema::dropIfExists('shows');
    }
}
