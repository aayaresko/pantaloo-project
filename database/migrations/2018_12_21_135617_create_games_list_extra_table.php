<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesListExtraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games_list_extra', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->integer('game_id')->unsigned()->index();
            $table->foreign('game_id')->references('id')->on('games_list');
            $table->integer('category_id')->unsigned()->index();
            $table->foreign('category_id')->references('id')->on('games_categories');
            $table->text('image')->nullable();
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
        Schema::drop('games_list_extra');
    }
}
