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
        Schema::table('venues', function (Blueprint $table) {
            $table->unsignedBigInteger('type')->nullable();
            $table->time('start_open_time')->nullable();
            $table->time('end_open_time')->nullable();
            $table->longText('rules')->nullable();
            $table->longText('safety')->nullable();
            $table->integer('popularity')->nullable();
            $table->decimal('start_price')->nullable();
            $table->decimal('end_price')->nullable();
            $table->foreign('type')->references('id')->on('venue_type')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('venues', function (Blueprint $table) {
            $table->dropForeign(['type']);
            $table->dropColumn('type');
            $table->dropColumn('start_open_time');
            $table->dropColumn('end_open_time');
            $table->dropColumn('rules');
            $table->dropColumn('safety');
            $table->dropColumn('popularity');
            $table->dropColumn('start_price');
            $table->dropColumn('end_price');
        });
    }
};
