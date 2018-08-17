<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('poster_id')->unsigned();
            $table->integer('thread_id')->unsigned();
            $table->integer('parent_comment_id')->unsigned()->nullable();
            $table->text('content');

            $table->foreign('poster_id')
                ->references('id')
                ->on('users');

            $table->foreign('thread_id')
                ->references('id')
                ->on('threads');
            
            $table->foreign('parent_comment_id')
                ->references('id')
                ->on('comments');
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
}
