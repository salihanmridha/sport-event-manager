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
        Schema::table('venue_booking', function (Blueprint $table) {
            $table->unsignedBigInteger('court_id');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('total_time')->nullable();
            $table->decimal('cost')->nullable();
            $table->foreign('court_id')->references('id')->on('courts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('venue_booking', function (Blueprint $table) {
            $table->dropForeign(['court_id']);
            $table->dropColumn('court_id');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropColumn('total_time');
            $table->dropColumn('cost');
        });
    }
};
