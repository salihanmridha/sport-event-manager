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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name');
            $table->string('last_name');
            $table->string('status');
            $table->string('gender', 20)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('phone')->unique();
            $table->text('bio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name');
            $table->string('last_name');
            $table->string('status');
            $table->string('gender', 20)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('phone')->unique();
            $table->text('bio');
        });
    }
};
