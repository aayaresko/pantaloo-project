<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonuses', function (Blueprint $table) {
            $table->increments('id');

            $table->decimal('min_sum', 20, 8)->nullable();
            $table->decimal('max_sum', 20, 8)->nullable();
            $table->integer('procent')->nullable();
            $table->tinyInteger('play_factor')->nullable();
            $table->tinyInteger('public');
            $table->string('name', 50);
            $table->string('descr', 500);
            $table->integer('rating')->default(0);

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
        Schema::drop('bonuses');
    }
}
