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
        Schema::create('receiveraddresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receiver_id')->constrained();
            $table->string('address');
            $table->foreignId('provincephil_id')->constrained();
            $table->foreignId('cityphil_id')->constrained();
            $table->foreignId('barangayphil_id')->constrained();
            $table->string('zip_code')->nullable();
            $table->integer('loczone')->nullable();
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
        Schema::dropIfExists('receiveraddresses');
    }
};
