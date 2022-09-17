<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_team', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id');
            $table
                ->foreign('event_id')
                ->references('id')
                ->on('events')
                ->onDelete('cascade');
            $table->unsignedBigInteger('team_id');
            $table
                ->foreign('team_id')
                ->references('id')
                ->on('teams')
                ->onDelete('cascade');
            $table->timestamps();
            $table->unique(['event_id', 'team_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_team');
    }
};
