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
            $table->foreignId('catextracharge_id')->after('note')->nullable()->constrained('catextracharges')->onDelete('cascade');
            $table->bigInteger('extracharge_amount')->after('catextracharge_id')->nullable();
            $table->boolean('box_replacement')->after('extracharge_amount')->default(0);
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
            $table->dropColumn('catextracharge_id');
            $table->dropColumn('extracharge_amount');
            $table->dropColumn('box_replacement');
        });
    }
};




