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
        Schema::create('venue_workday', function (Blueprint $table) {
            $table->unsignedBigInteger('venue_id');          
            $table->string('workday_id', 50);
            $table->unique(['venue_id', 'workday_id']);
            //$table->index(['venue_id', 'workday_id']);
            $table->foreign('venue_id')->references('id')->on('venues')->onDelete('cascade');
            $table->foreign('workday_id')->references('code')->on('weekdays')->onUpdate('cascade')->onDelete('cascade');
            $table->index('venue_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('venue_workday');
    }
};
