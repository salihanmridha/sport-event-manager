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
        Schema::create('venue_sport', function (Blueprint $table) {
            $table->unsignedBigInteger('venue_id');
            $table
                ->foreign('venue_id')
                ->references('id')
                ->on('venues')
                ->onDelete('cascade');
            $table->unsignedBigInteger('sport_id');
            $table
                ->foreign('sport_id')
                ->references('id')
                ->on('sports')
                ->onDelete('cascade');
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
        Schema::dropIfExists('venue_sport');
    }
};
