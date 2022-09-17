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
        Schema::table('teams', function (Blueprint $table) {
            $table
                ->string('gender')
                ->nullable()
                ->change();
            $table
                ->text('bio')
                ->nullable()
                ->change();
            $table
                ->integer('age_group')
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->string('gender');
            $table->text('bio');
            $table->text('age_group');
        });
    }
};
