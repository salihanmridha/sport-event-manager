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
        Schema::table('venue_booking', function (Blueprint $table) {
            $table->string('court_name',255)->nullable();
            $table->string('address',255)->nullable();
            $table->decimal('price')->nullable();
            $table->unsignedBigInteger('sport_id')->nullable();
            $table->softDeletes();
            $table->foreign('sport_id')->references('id')->on('sports')->onDelete('set null');
        });
        Schema::table('courts', function (Blueprint $table) {
          $table->dropForeign(['sport_id']);
        });
        Schema::table('courts', function (Blueprint $table) {
          $table->foreign('sport_id')->references('id')->on('sports')->onDelete('set null');
        }); 
        Schema::table('posts', function (Blueprint $table) {
          $table->unsignedBigInteger('post_by')->nullable();
          $table->foreign('post_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('venue_booking', function (Blueprint $table) {
            $table->dropForeign(['sport_id']);
            $table->dropColumn('court_name');
            $table->dropColumn('address');
            $table->dropColumn('price');
            $table->dropColumn('sport_id');
        });
        Schema::table('posts', function (Blueprint $table) {
          $table->dropForeign(['post_by']);
            $table->dropColumn('post_by');
        });
    }
};
