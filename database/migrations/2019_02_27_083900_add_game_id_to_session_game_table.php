<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGameIdToSessionGameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games_pantallo_session_game', function (Blueprint $table) {
            $table->integer('game_id')->nullable()->unsigned()->after('gamesession_id');
            $table->foreign('game_id')->references('id')->on('games_list');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games_pantallo_session_game', function (Blueprint $table) {
            $table->dropForeign(['game_id']);
            $table->dropColumn(['game_id']);
        });
    }
}
