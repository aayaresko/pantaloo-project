<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteForeignColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games_pantallo_free_rounds', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games_pantallo_free_rounds', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
}
