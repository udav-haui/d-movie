<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFilmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('films', function (Blueprint $table) {
            $table->increments('id')->comment('Film identifier');
            $table->tinyInteger('status')->comment('Status of film');
            $table->string('title')->comment('Title of film');
            $table->text('poster')->nullable(false)->comment('Poster of film');
            $table->string('director')->nullable()->comment('Director of film');
            $table->string('cast')->nullable()->comment('Cast in film');
            $table->string('genre')->nullable()->comment('Genre(type) of film');
            $table->unsignedSmallInteger('running_time')->default(0)->comment('Duration of film');
            $table->string('language')->nullable()->comment('Language of film');
            $table->text('description')->nullable()->comment('Film short description');
            $table->date('release_date')->useCurrent()->comment('Film release date');
            $table->string('mark')->default('p')->comment('Film certificate');
            $table->text('trailer')->nullable()->comment('Film trailer');
            $table->tinyInteger('is_coming_soon')->default(1)->comment('This schedule is coming for show');
            $table->tinyInteger('is_open_sale_ticket')->default(1)->comment('This film be open sale ticket');
            $table->tinyInteger('is_sneak_show')->default(0)->comment('Sneak show for this schedule');
            $table->timestamp('created_at')->useCurrent()->comment('Film create time');
            $table->timestamp('updated_at')->useCurrent()->comment('Film update time');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('films');
    }
}
