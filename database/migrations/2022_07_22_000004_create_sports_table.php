<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsTable extends Migration
{
    public function up()
    {
        Schema::create('sports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->integer('max_player_per_team')->nullable();
            $table->integer('min_player_per_team')->nullable();
            $table->string('is_require_choose_role')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
