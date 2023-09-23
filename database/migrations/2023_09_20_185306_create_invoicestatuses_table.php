<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoicestatuses', function (Blueprint $table) {
            $table->id();
            $table->string('generated_invoice');
            $table->string('manual_invoice')->nullable();
            $table->foreignId('trackstatus_id')->reference('id')->on('trackstatuses')->constrained();
            $table->foreignId('provincephil_id')->reference('id')->on('provincephils')->constrained();
            $table->foreignId('cityphil_id')->reference('id')->on('cityphils')->constrained();
            $table->foreignId('booking_id')->reference('id')->on('bookings')->constrained();
            $table->foreignId('batch_id')->reference('id')->on('batches')->constrained();
            $table->foreignId('user_id')->reference('id')->on('users')->constrained();
            $table->foreignId('receiver_id')->reference('id')->on('receivers')->constrained();
            $table->foreignId('sender_id')->reference('id')->on('senders')->constrained();
            $table->foreignId('boxtype_id')->reference('id')->on('boxtypes')->constrained();
            $table->date('date_update');
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('invoicestatuses');
    }
};
