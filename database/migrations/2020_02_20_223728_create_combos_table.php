<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCombosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combos', function (Blueprint $table) {
            $table->increments('id')->comment('Combo identifier.');
            $table->tinyInteger('status')->default(1)->comment('Combo status.');
            $table->string('name')->comment('Combo name');
            $table->string('description')->comment('Combo description');
            $table->float('price')->default(0)->comment('Combo price');
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
        Schema::dropIfExists('combos');
    }
}
