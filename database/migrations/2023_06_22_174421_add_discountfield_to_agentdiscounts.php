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
        Schema::table('agentdiscounts', function (Blueprint $table) {
            $table->foreignId('boxtype_id')->nullable()->reference('id')->on('boxtypes')->constrained()->after('zone_id');
            $table->boolean('is_active')->default(true)->after('boxtype_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agentdiscounts', function (Blueprint $table) {
            $table->dropColumn('boxtype_id');
            $table->dropColumn('is_active');
        });
    }
};
