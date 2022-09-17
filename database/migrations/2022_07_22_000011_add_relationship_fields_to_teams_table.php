<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToTeamsTable extends Migration
{
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->unsignedBigInteger('sport_id')->nullable();
            $table->foreign('sport_id', 'sport_fk_7026794')->references('id')->on('sports');
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->foreign('creator_id', 'creator_fk_7026795')->references('id')->on('users');
            $table->unsignedBigInteger('coach_id')->nullable();
            $table->foreign('coach_id', 'coach_fk_7026796')->references('id')->on('users');
        });
    }
}
