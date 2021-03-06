<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLastActionGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // TODO Lior - What last_action_games table is used for?
        Schema::create('last_action_games', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('game_id')->unsigned();
            $table->timestamp('last_action')->nullable();
            $table->timestamp('last_game')->nullable();
            $table->string('gamesession_id');
            $table->integer('number_games')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('last_action_games');
    }
}
