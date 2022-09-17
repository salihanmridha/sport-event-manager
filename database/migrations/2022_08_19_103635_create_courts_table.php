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
        Schema::create('courts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('venue_id');
            $table->unsignedBigInteger('sport_id')->nullable();
            $table->string('name', 255)->nullable();
            $table->decimal('price')->default(0);
            $table->integer('weight')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('venue_id')->references('id')->on('venues')->onDelete('cascade');
            $table->foreign('sport_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courts');
    }
};
