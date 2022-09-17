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
        if (Schema::hasColumn('sport_user', 'id')) {
            Schema::table('sport_user', function (Blueprint $table) {
                $table->dropColumn('id');
            });
        }
        Schema::table('sport_user', function (Blueprint $table) {
            $keyExists = DB::select(
                DB::raw(
                    'SHOW KEYS FROM sport_user
                WHERE Key_name=\'sport_user_user_id_sport_id_unique\''
                )
            );
            if (!$keyExists) {
                $table->unique(['user_id', 'sport_id']);
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
        if (!Schema::hasColumn('sport_user', 'id')) {
            Schema::table('sport_user', function (Blueprint $table) {
                $table->id();
            });
        }
        Schema::table('sport_user', function (Blueprint $table) {
            $keyExists = DB::select(
                DB::raw(
                    'SHOW KEYS FROM sport_user
              WHERE Key_name=\'sport_user_user_id_sport_id_unique\''
                )
            );
            if ($keyExists) {
                $table->dropUnique(['user_id', 'sport_id']);
            }
        });
    }
};
