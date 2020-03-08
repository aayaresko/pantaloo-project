<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesRestrictionByCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restriction_games_by_country', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('game_id')->unsigned()->index();
            $table->foreign('game_id')->references('id')->on('games_list');
            $table->string('code_country')->index();
            $table->tinyInteger('mark')->default(0)->index();
            $table->timestamps();

            $table->unique(['game_id', 'code_country', 'mark'], 'unique_restriction_game_id_code_country_mark');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('restriction_games_by_country');
    }
}
