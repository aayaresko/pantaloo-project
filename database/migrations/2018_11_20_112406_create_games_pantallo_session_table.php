<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesPantalloSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games_pantallo_session', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('system_id')->unsigned()->index();
            $table->string('username');
            $table->decimal('balance', 14, 5);
            // TODO Lior - what is "currencycode" column in "games_pantallo_session" table?
            $table->string('currencycode');
            $table->dateTime('created');
            $table->decimal('agent_balance', 14, 5);
            $table->string('sessionid')->unique();
            $table->boolean('status')->default(0);
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
        Schema::drop('games_pantallo_session');
    }
}
