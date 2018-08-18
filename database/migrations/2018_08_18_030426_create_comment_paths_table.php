<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentPathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_paths', function (Blueprint $table) {

            $table->integer('ancestor_id')->unsigned();
            $table->integer('descendant_id')->unsigned();
            $table->integer('tree_depth')->unsigned();

            $table->primary(['ancestor_id', 'descendant_id']);

            $table->foreign('ancestor_id')
                ->references('id')
                ->on('comments')
                ->onDelete('cascade');

            $table->foreign('descendant_id')
                ->references('id')
                ->on('comments')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_paths');
    }
}
