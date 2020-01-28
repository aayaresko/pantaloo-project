<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesFreeRoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games_pantallo_free_rounds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');

            // TODO Lior - what is the logic for round?
            $table->integer('round');

            // TODO Lior check logic of valid to and free round policy
            $table->dateTime('valid_to');

            $table->string('created');
            $table->string('free_round_id')->unique();
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
        Schema::drop('games_pantallo_free_rounds');
    }
}
