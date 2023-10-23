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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_invoice');
            $table->string('manual_invoice')->nullable();
            $table->foreignId('sender_id')->reference('id')->on('senders')->constrained();
            $table->foreignId('senderaddress_id')->reference('id')->on('senderaddresses')->constrained();
            $table->foreignId('receiver_id')->reference('id')->on('receivers')->constrained();
            $table->foreignId('receiveraddress_id')->reference('id')->on('receiveraddresses')->constrained();
            $table->foreignId('boxtype_id')->reference('id')->on('boxtypes')->constrained();
            $table->foreignId('servicetype_id')->reference('id')->on('servicetypes')->constrained();
            $table->foreignId('agent_id')->nullable()->reference('id')->on('agents')->constrained();
            $table->foreignId('zone_id')->reference('id')->on('zones')->constrained();
            $table->foreignId('branch_id')->reference('id')->on('branchs')->constrained();
            $table->foreignId('batch_id')->reference('id')->on('batchs')->constrained();
            $table->date('booking_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->foreignId('discount_id')->nullable()->reference('id')->on('discounts')->constrained();
            $table->boolean('is_pickup')->default(0);
            $table->unsignedBigInteger('total_price');
            $table->unsignedBigInteger('irregular_length')->nullable();
            $table->unsignedBigInteger('irregular_width')->nullable();
            $table->unsignedBigInteger('irregular_height')->nullable();
            $table->unsignedBigInteger('total_inches')->nullable();
            $table->boolean('is_paid')->default(0);
            $table->unsignedBigInteger('payment_balance');
            $table->BigInteger('refund_amount')->nullable();
            $table->string('dimension')->virtualAs('concat(irregular_length, \' \', irregular_width, \' \', irregular_height)');
            $table->text('note')->nullable();
            $table->foreignId('catextrcharge_id')->constrained();
            $table->boolean('box_replacement');
            $table->foreignId('user_id')->constrained();
            $table->boolean('is_edit')->default(true);
            $table->boolean('is_agent')->default(false);
            $table->foreignId('agentdiscount_id')->nullable()->reference('id')->on('agentdiscounts')->constrained();
            $table->boolean('is_delivered')->default(false);
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
        Schema::dropIfExists('bookings');
    }
};
