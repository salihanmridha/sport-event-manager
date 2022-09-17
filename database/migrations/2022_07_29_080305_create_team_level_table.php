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
      Schema::create('team_level', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('code',50)->index();
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
      Schema::dropIfExists('team_level');
    }
};
