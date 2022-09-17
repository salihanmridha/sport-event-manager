<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToSportsTable extends Migration
{
    public function up()
    {
        Schema::table('sports', function (Blueprint $table) {
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->foreign('creator_id', 'creator_fk_7026785')->references('id')->on('users');
        });
    }
}
