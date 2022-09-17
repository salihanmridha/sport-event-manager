<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('gender')->nullable();
            $table->string('level')->nullable();
            $table->string('oganization_name')->nullable();
            $table->string('oganization_url')->nullable();
            $table->string('division')->nullable();
            $table->string('season')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
