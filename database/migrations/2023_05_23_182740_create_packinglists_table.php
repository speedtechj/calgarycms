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
        Schema::create('packinglists', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('quantity')->nullable();
            $table->foreignId('booking_id')->constrained();
            $table->foreignId('sender_id')->constrained();
            $table->foreignId('packlistitem_id')->nullable()->constrained();
            $table->string('description')->nullable();
            $table->string('packlistdoc')->nullable();
            $table->string('waverdoc')->nullable();
            $table->bigInteger('price');
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
        Schema::dropIfExists('packinglists');
    }
};
