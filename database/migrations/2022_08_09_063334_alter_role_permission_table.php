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
        Schema::table('roles', function (Blueprint $table) {
            if (!Schema::hasColumn('roles', 'type')) {
                $table->integer('type')->default(0);
            }
        });
        Schema::table('permissions', function (Blueprint $table) {
            if (!Schema::hasColumn('permissions', 'type')) {
                $table->integer('type')->default(0);
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
        Schema::table('roles', function (Blueprint $table) {
            if (Schema::hasColumn('roles', 'type')) {
                $table->dropColumn('type');
            }
        });

        Schema::table('permissions', function (Blueprint $table) {
          if (Schema::hasColumn('permissions', 'type')) {
              $table->dropColumn('type');
          }
      });
    }
};
