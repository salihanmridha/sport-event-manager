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
      Schema::table('teams', function (Blueprint $table) {
        $dropcol = ['gender', 'level', 'coach_id'];
        foreach ($dropcol as $v) {
          if (Schema::hasColumn('teams', $v)) {
            if ($v == 'coach_id') {
              //$table->dropForeign('coach_id');
              //$table->dropForeign('teams_coach_fk_7026796_foreign');
              $table->dropForeign('coach_fk_7026796');
            }
            $table->dropColumn($v);
          }
        }
      });

      Schema::table('teams', function (Blueprint $table) {
        $table->enum('gender', [1, 2, 0])->nullable();
        $table->integer('start_age')->nullable();
        $table->integer('end_age')->nullable();
        $table->unsignedBigInteger('level_id')->nullable();
        $table->foreign('level_id')->references('id')->on('team_level');
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
      Schema::table('teams', function (Blueprint $table) {
        $dropcol = ['gender', 'start_age', 'end_age', 'level_id', 'bio'];
        foreach ($dropcol as $v) {
          if (Schema::hasColumn('teams', $v)) {
            if ($v == '') {
              $table->dropForeign('teams_level_id_foreign');
            }
            $table->dropColumn($v);
          }
        }
      }); 

      Schema::table('teams', function (Blueprint $table) {
        $table->string('gender')->nullable();
        $table->string('level')->nullable();
        $table->unsignedBigInteger('coach_id')->nullable();
        $table->foreign('coach_id', 'coach_fk_7026796')->references('id')->on('users');
      });
    }
};

