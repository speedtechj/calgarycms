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
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name')->virtualAs('concat(first_name, \' \', last_name)');
            $table->string('address');
            $table->foreignId('provincecan_id')->contrained();
            $table->foreignId('citycan_id')->constrained();
            $table->string('postal_code');
            $table->string('email')->unique();
            $table->date('date_of_birth');
            $table->string('filedoc')->nullable();
            $table->string('mobile_no')->unique();
            $table->string('home_no');
            $table->date('date_hired');
            $table->text('note');
            $table->boolean('agent_type')->default(1);
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
        Schema::dropIfExists('agents');
    }
};
