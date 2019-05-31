<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_sums', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
          //  $table->decimal('sum', 14, 5);
            $table->decimal('deposits', 14, 5);
            $table->decimal('bets', 14, 5);
            $table->decimal('wins', 14, 5);
            $table->decimal('sum', 14, 5);
            $table->decimal('bonus', 14, 5);
            $table->integer('bet_count');
            $table->decimal('percent')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('casino_fit')->nullable();
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
        Schema::drop('user_sums');
    }
}
