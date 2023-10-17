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
        Schema::create('statuscategories', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->boolean('is_active')->default(true);
            $table->foreignId('user_id')->reference('id')->on('users')->constrained();
            $table->foreignId('branch_id')->reference('id')->on('branches')->constrained();
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
        Schema::dropIfExists('statuscategories');
    }
};
