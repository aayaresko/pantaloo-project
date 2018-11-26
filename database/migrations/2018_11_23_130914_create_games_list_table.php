<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games_list', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('system_id')->unsigned()->unique();
            $table->string('name');
            $table->integer('type_id')->unsigned();
            $table->foreign('type_id')->references('id')->on('games_types');
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('games_categories');
            $table->text('details')->nullable();
            $table->boolean('mobile');
            $table->text('image');
            $table->text('image_preview');
            $table->text('image_filled');
            $table->text('image_background');
            $table->integer('rating')->unsigned();
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
        Schema::drop('games_list');
    }
}
