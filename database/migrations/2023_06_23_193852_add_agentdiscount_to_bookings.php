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
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('agentdiscount_id')->nullable()->below('discount_id')->reference('id')->on('agentdiscounts')->constrained();
            $table->boolean('is_agent')->default(false)->below('agentdiscount_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('agentdiscount_id');
            $table->dropColumn('is_agent');
        });
    }
};
