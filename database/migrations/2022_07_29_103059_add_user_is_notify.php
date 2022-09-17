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
            $table->boolean('is_notify_email');
            $table->boolean('is_notify_sms');
            $table->boolean('is_notify_push');
            $table->boolean('is_marketing');
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
            $table->dropColumn(['is_notify_email']);
            $table->dropColumn(['is_notify_sms']);
            $table->dropColumn(['is_notify_push']);
            $table->dropColumn(['is_marketing']);
        });
    }
};
