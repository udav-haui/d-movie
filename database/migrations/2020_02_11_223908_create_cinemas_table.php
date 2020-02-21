<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCinemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cinemas', function (Blueprint $table) {
            $table->increments('id')->comment('Cinema\'s Identifier');
            $table->tinyInteger('status')->comment('Status');
            $table->string('name')->default('Unnamed')->comment('Cinema\'s name');
            $table->string('address')->comment('Cinema\'s Address');
            $table->string('province')->comment('Be place in province');
            $table->char('phone', 12)->nullable()->comment('Hot line of cinema');
            $table->text('description')->nullable()->comment('Describe about this cinema');
            $table->timestamp('created_at')->useCurrent()->comment('Create time');
            $table->timestamp('updated_at')->useCurrent()->comment('Update time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cinemas');
    }
}
