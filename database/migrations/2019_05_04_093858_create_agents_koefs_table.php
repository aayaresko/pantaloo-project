<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentsKoefsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // TODO Lior - What koef means in agents_koefs table?
        // TODO Max - maybe russian word коэфициент - coefficient
        Schema::create('agents_koefs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->decimal('koef')->default(0);
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
        Schema::drop('agents_koefs');
    }
}
