<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $sm;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      $this->sm = Schema::getConnection()->getDoctrineSchemaManager();
        Schema::table('sports', function (Blueprint $table) {
            $indexesFound = $this->sm->listTableIndexes('sports');
            if (!array_key_exists('code', $indexesFound)) {
              $table->unique('code')->index('code');
            }
        });
        Schema::table('countries', function (Blueprint $table) {
          $indexesFound = $this->sm->listTableIndexes('countries');
          if (!array_key_exists('code', $indexesFound)) {
            $table->unique('code')->index('code');
          }
        });
        Schema::table('team_level', function (Blueprint $table) {
            $table->unique('code');
        });
        Schema::table('organization_role', function (Blueprint $table) {
            $table->unique('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sports', function (Blueprint $table) {
            $table->dropUnique('code')->dropIndex('code');
        });
        Schema::table('countries', function (Blueprint $table) {
          $table->dropUnique('code')->dropIndex('code');
        });
        Schema::table('team_level', function (Blueprint $table) {
          $table->dropUnique('code');
        });
        Schema::table('organization_role', function (Blueprint $table) {
          $table->dropUnique('code');
        });
    }
};
