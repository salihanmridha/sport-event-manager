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
      Schema::create('announcements', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('type')->default('announcement');
          $table->string('title');
          $table->text('about')->nullable();
          $table->string('start_date');
          $table->string('end_date');
          $table->string('status')->default('unpublish');
          $table->unsignedBigInteger('creator_id')->nullable();
          $table->timestamps();
          $table->softDeletes();
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
        Schema::dropIfExists('announcements');
    }
};
