<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddColumnLastActionGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('last_action_games', function (Blueprint $table) {
            $table->timestamp('last_game')->after('last_action')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('last_action_games', function (Blueprint $table) {
            $table->dropColumn(['last_game']);
        });
    }
}
