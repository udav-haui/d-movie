<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateStaticPagesTable
 */
class CreateStaticPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('static_pages', function (Blueprint $table) {
            $table->increments('id')->comment('Page identifier.');
            $table->tinyInteger('status')->default(1)->comment('Page status');
            $table->string('name')->comment('Page name.');
            $table->string('slug')->comment('Page slug.');
            $table->char('language', 2)->comment('Page content language.');
            $table->text('content')->nullable()->comment('Page content.');
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
        Schema::dropIfExists('static_pages');
    }
}
