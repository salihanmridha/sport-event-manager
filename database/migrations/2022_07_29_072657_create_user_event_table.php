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
        Schema::create('user_event', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id');
            $table
                ->foreign('event_id')
                ->references('id')
                ->on('events')
                ->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->unsignedBigInteger('position_id')->nullable();
            $table
                ->foreign('position_id')
                ->references('id')
                ->on('event_position')
                ->onDelete('cascade');
            $table->unsignedBigInteger('event_squad_id')->nullable();
            $table
                ->foreign('event_squad_id')
                ->references('id')
                ->on('event_squad')
                ->onDelete('cascade');
            $table->timestamps();
            $table->unique(['event_id', 'user_id']);
            $table->unique(['event_id', 'user_id', 'event_squad_id']);
            $table->unique([
                'event_id',
                'user_id',
                'event_squad_id',
                'position_id',
            ]);
            $table->unique(['event_id', 'event_squad_id', 'position_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_event');
    }
};
