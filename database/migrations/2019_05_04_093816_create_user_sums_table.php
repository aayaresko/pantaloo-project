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
        // TODO Lior - Why do we use two decimal versions? 1st decimal(20,8) and second is decimal (14,5) we should choose one.
        Schema::create('user_sums', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->decimal('deposits', 14, 5);
            $table->decimal('bets', 14, 5);
            $table->decimal('wins', 14, 5);
            $table->decimal('sum', 14, 5);
            $table->decimal('sum', 14, 5);
            $table->decimal('bonus', 14, 5);
            $table->integer('bet_count');
            $table->decimal('percent')->nullable();
            // TODO Lior - What is parent_id in user_sums table?
            $table->integer('parent_id')->nullable();
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
