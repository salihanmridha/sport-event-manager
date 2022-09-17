<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('event_type')->nullable();
            $table->integer('number_of_team')->nullable();
            $table->integer('player_per_team')->nullable();
            $table->string('application_type')->nullable();
            $table->string('description')->nullable();
            $table->datetime('start_date_time')->nullable();
            $table->datetime('end_date_time')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down()
    {
        Schema::drop('events');
    }
}
