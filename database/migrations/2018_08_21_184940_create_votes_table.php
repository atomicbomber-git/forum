<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
                        
            $table->integer('voter_id')->unsigned();
            $table->integer('votable_id')->unsigned();
            $table->string('votable_type');
            $table->string('vote_type');
            
            $table->foreign('voter_id')
                ->references('id')
                ->on('users');

            $table->primary(['voter_id', 'votable_id', 'votable_type']);
            $table->index('voter_id');
            $table->index(['votable_id', 'votable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('votes');
    }
}
