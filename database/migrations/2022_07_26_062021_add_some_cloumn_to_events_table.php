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
        Schema::table('events', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->integer('max_team')->nullable();
            $table->longText('caption')->nullable();
            $table->integer('max_player_per_team')->nullable();
            $table->boolean('is_unlimit_max')->default(0);
            $table->longText('about')->nullable();
            $table->longText('mechanics')->nullable();
            $table->integer('gender')->nullable();
            $table->integer('start_age')->nullable();
            $table->integer('end_age')->nullable();
            $table->string('location')->nullable();
            $table->decimal('lat', 15, 7);
            $table->decimal('long', 15, 7);
            $table->boolean('is_public')->default(0);
            $table->boolean('is_set_role')->default(0);
            $table->boolean('is_paid')->default(0);
            $table->decimal('fee', 5, 2)->nullable();
            $table->string('private_code')->nullable();
            $table->integer('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'type',
                'max_team',
                'caption',
                'max_player_per_team',
                'is_unlimit_max',
                'about',
                'location',
                'lat',
                'long',
                'is_public',
                'is_set_role',
                'is_paid',
                'fee',
                'mechanics',
                'gender',
                'start_age',
                'end_age',
                'private_code',
                'status',
            ]);
        });
    }
};
