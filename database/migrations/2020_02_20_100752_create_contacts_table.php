<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id')->comment('Contact\'s Identifier.');
            $table->tinyInteger('status')->comment('Contact\'s Status.');
            $table->unsignedInteger('cinema_id')->index()->comment('This contact for associate cinema.');
            $table->string('contact_name')->comment('Contact\'s Customer name.');
            $table->string('contact_email')->comment('Contact\'s Email.');
            $table->string('contact_phone')->comment('Contact\'s Customer phone.');
            $table->text('contact_content')->comment('Contact\'s Customer content.');
            $table->timestamp('created_at')->useCurrent()->comment('Send time');

            $table->foreign('cinema_id')->references('id')
                ->on('cinemas')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
