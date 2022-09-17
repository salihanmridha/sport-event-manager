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
        Schema::create('interactions', function (Blueprint $table) {
            $table->unsignedBigInteger('first_id')->index();
            $table->string('first_type',255);
            $table->unsignedBigInteger('last_id')->index();
            $table->string('last_type',255);
            $table->integer('status');
            $table->timestamps();
            $table->unique(['first_id','first_type','last_id','last_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interactions');
    }
};
