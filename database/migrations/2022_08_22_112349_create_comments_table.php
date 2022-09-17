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
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('author_id');
            $table->string('author_type',50);
            $table->unsignedBigInteger('post_id')->index();
            $table->unsignedBigInteger('reply_id')->nullable();
            $table->longText('content');
            $table->unsignedBigInteger('post_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('reply_id')->references('id')->on('comments')->onDelete('cascade');
            $table->foreign('post_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['author_id', 'author_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
