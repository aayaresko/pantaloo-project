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
        Schema::create('games_restriction_by_country', function (Blueprint $table) {
//            $table->increments('id');
//            $table->integer('game_id')->unsigned()->index();
//            $table->foreign('game_id')->references('id')->on('games_list');
//            $table->integer('type_id')->unsigned()->index();
//            $table->foreign('type_id')->references('id')->on('games_types');
//            $table->tinyInteger('extra')->default(0)->index();
//            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('games_restriction_by_country');
    }
}
