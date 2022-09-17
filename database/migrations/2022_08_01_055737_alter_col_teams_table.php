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
            if (Schema::hasColumn('teams', 'gender')) {
                $table->dropColumn('gender');
            }
        });
        Schema::table('teams', function (Blueprint $table) {
            if (!Schema::hasColumn('teams', 'age_group')) {
              $table->integer('age_group');
            }
            if (!Schema::hasColumn('teams', 'gender')) {
              $table->string('gender');
            }
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
            $table->dropColumn('gender');
            $table->string('gender');
            $table->dropColumn('age_group');
        });
    }
};
