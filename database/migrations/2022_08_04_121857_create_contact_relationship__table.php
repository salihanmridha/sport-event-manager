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
        Schema::create('contact_relationship', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('code',50)->index()->unique();
          $table->string('name', 255);
          $table->unsignedBigInteger('creator_id')->nullable();
          $table->timestamps();
          $table->foreign('creator_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_relationship');
    }
};
