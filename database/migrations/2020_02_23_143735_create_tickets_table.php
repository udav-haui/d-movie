<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id')->comment('The Booking identifier');
            $table->tinyInteger('status')->default(1)->comment('The booking status');
            $table->string('booking_code')->unique()->comment('The booking code');
            $table->integer('qty')->default(1)->comment('The ticket number in booking');
            $table->float('amount')->default(0)->comment('The booking subtotal');
            $table->string('message')->nullable()->comment('Transaction message');
            $table->unsignedInteger('combo_id')->nullable()->index()->comment('Combo');
            $table->unsignedInteger('user_id')->index()->comment('The user has book this order');
            $table->timestamp('created_at')->useCurrent()->comment('Book time');
            $table->timestamp('updated_at')->useCurrent()->comment('Modify time');

            $table->foreign('combo_id')->references('id')
                ->on('combos')
                ->onUpdate('cascade')
                ->onDelete('no action');

            $table->foreign('user_id')->references('id')
                ->on('users')
                ->onDelete('no action')
                ->onUpdate('cascade');
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('booking_id')->index()->comment('The booking identifier');
            $table->tinyInteger('status')->comment('Ticket status');
            $table->string('ticket_code')->unique()->index()->comment('The ticket code');
            $table->float('price')->default(0)->comment('Ticket price');
            $table->unsignedInteger('seat_id')->index()->comment('The booking belong to');
            $table->unsignedInteger('time_id')->index()->comment('The showtime belong to');
            $table->timestamp('created_at')->comment('Create time');

            $table->foreign('booking_id')->references('id')
                ->on('bookings')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('seat_id')->references('id')
                ->on('seats')
                ->onUpdate('cascade')
                ->onDelete('no action');
            $table->foreign('time_id')->references('id')
                ->on('times')
                ->onUpdate('cascade')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('tickets');
    }
}
