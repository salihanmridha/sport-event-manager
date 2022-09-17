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
        Schema::create('venue_booking', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('venue_id');
            $table
                ->foreign('venue_id')
                ->references('id')
                ->on('venues')
                ->onDelete('cascade');
            $table->unsignedBigInteger('event_id');
            $table
                ->foreign('event_id')
                ->references('id')
                ->on('events')
                ->onDelete('cascade');
            $table->unsignedBigInteger('creator_id')->nullable();
            $table
                ->foreign('creator_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            $table->tinyInteger('response')->default(0);
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
        Schema::dropIfExists('venue_booking');
    }
};
