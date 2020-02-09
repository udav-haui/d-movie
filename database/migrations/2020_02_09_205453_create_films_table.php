<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id')->comment('Film identifier');
            $table->string('title')->comment('Title of film');
            $table->text('poster')->nullable(false)->comment('Poster of film');
            $table->string('director')->nullable(true)->comment('Director of film');
            $table->string('cast')->nullable(true)->comment('Cast in film');
            $table->string('genre')->nullable(true)->comment('Genre(type) of film');
            $table->unsignedInteger('running_time')->default(0)->comment('Duration of film');
            $table->string('language')->nullable(true)->comment('Language of film');
            $table->text('description')->nullable(true)->comment('Film short description');
            $table->date('release_date')->useCurrent()->comment('Film release date');
            $table->string('mark')->default('p')->comment('Film certificate');
            $table->text('trailer')->nullable(true)->comment('Film trailer');
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
