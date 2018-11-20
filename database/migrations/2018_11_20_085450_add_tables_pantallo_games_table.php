<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTablesPantalloGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games_pantallo_games', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('system_id');
            $table->string('username');
            $table->decimal('balance', 14, 5);
            $table->string('currencycode');
            $table->dateTime('created');
            $table->decimal('agent_balance', 14, 5);
            $table->string('sessionid');
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
        Schema::table('games_pantallo_games', function (Blueprint $table) {
            //
        });
    }
}
