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
        Schema::create('senderaddresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained();
            $table->string('address');
            $table->bigInteger('provincecan_id');
            $table->bigInteger('citycan_id');
            $table->string('quadrant')->nullable();
            $table->string('postal_code');
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('senderaddresses');
    }
};
