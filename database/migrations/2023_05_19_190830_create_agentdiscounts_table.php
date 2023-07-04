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
        Schema::create('agentdiscounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->reference('id')->on('agents')->constrained();
            $table->foreignId('servicetype_id')->reference('id')->on('servicetypes')->constrained();
            $table->foreignId('zone_id')->reference('id')->on('zones')->constrained();
            $table->foreignId('user_id')->reference('id')->on('users')->constrained();
            $table->foreignId('branch_id')->reference('id')->on('branchs')->constrained();
            $table->string('code');
            $table->string('description');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('discount_amount');
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
        Schema::dropIfExists('agentdiscounts');
    }
};
