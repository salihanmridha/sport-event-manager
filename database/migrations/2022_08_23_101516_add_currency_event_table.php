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
        Schema::table('events', function (Blueprint $table) {
            $table->unsignedBigInteger('currency')->nullable();
            $table->foreign('currency')->references('id')->on('currencies')->onDelete('set null');
        });
        Schema::table('user_event', function (Blueprint $table) {
            $table->unsignedBigInteger('currency')->nullable();
            $table->foreign('currency')->references('id')->on('currencies')->onDelete('set null');
        });
        Schema::table('event_team', function (Blueprint $table) {
            $table->unsignedBigInteger('currency')->nullable();
            $table->foreign('currency')->references('id')->on('currencies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['currency']);
            $table->dropColumn('currency');
        });
        Schema::table('user_event', function (Blueprint $table) {
            $table->dropForeign(['currency']);
            $table->dropColumn('currency');
        });
        Schema::table('event_team', function (Blueprint $table) {
            $table->dropForeign(['currency']);
            $table->dropColumn('currency');
        });
    }
};
