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
        Schema::create('likes', function (Blueprint $table) {
            $table->unsignedBigInteger('source_id'); // id user or team
            $table->string('source_type',50); // user or team
            $table->unsignedBigInteger('target_id'); // id post or comment
            $table->string('target_type',50); // post or comment
            $table->timestamps();
            $table->unique(['source_id', 'source_type' , 'target_id' , 'target_type']);
            $table->index(['target_id', 'target_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
    }
};
