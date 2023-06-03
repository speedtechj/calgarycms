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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name')->virtualAs('concat(first_name, \' \', last_name)');
            $table->string('address');
            $table->string('provincecan_id');
            $table->string('citycan_id');
            $table->string('postal_code');
            $table->date('birth_date');
            $table->string('file_doc')->nullable();
            $table->string('mobile_no');
            $table->string('home_no')->nullable();
            $table->date('date_hire');
            $table->boolean('is_active')->default(1);
            $table->string('email')->unique();
            $table->text('note')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('branch_id')->constrained('classes');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
