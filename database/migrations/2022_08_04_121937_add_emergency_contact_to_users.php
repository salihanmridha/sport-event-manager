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
            $table->string('ec_fullname')->nullable();
            $table->unsignedBigInteger('ec_relationship')->nullable();
            $table
                ->foreign('ec_relationship')
                ->references('id')
                ->on('contact_relationship')
                ->onDelete('set null');
            $table->string('ec_main_pcode')->nullable();
            $table->string('ec_main_pnum')->nullable();
            $table->string('ec_alt_pcode')->nullable();
            $table->string('ec_alt_pnum')->nullable();
            $table->string('ec_email')->nullable();
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
            $table->dropColumn('ec_fullname');
            $table->dropForeign('ec_relationship');
            $table->dropColumn('ec_relationship');
            $table->dropColumn('ec_main_pcode');
            $table->dropColumn('ec_main_pnum');
            $table->dropColumn('ec_alt_pcode');
            $table->dropColumn('ec_alt_pnum');
            $table->dropColumn('ec_email');
        });
    }
};
