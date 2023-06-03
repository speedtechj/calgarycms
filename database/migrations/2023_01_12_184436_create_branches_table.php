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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('branchid')->unique();
            $table->string('business_name');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('address');
            $table->string('provincecan_id');
            $table->string('citycan_id');
            $table->string('postal_code');
            $table->string('mobile_no');
            $table->string('phone_no')->nullable();
            $table->string('email')->unique();
            $table->text('note')->nullable();
            $table->string('file_doc')->nullable();
            $table->date('birth_date')->nullable();
            $table->bigInteger('user_id');
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
        Schema::dropIfExists('branches');
    }
};
