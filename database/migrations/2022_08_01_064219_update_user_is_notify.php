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
            $table->boolean('is_notify_email')->default(0)->change();
            $table->boolean('is_notify_sms')->default(0)->change();
            $table->boolean('is_notify_push')->default(0)->change();
            $table->boolean('is_marketing')->default(0)->change();
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
            $table->boolean('is_notify_email')->change();
            $table->boolean('is_notify_sms')->change();
            $table->boolean('is_notify_push')->change();
            $table->boolean('is_marketing')->change();
        });
    }
};
