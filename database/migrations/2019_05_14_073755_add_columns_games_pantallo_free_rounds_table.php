<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsGamesPantalloFreeRoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games_pantallo_free_rounds', function (Blueprint $table) {
            $table->tinyInteger('deleted')->default(0)->after('free_round_id');
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
            $table->dropColumn('deleted');
        });
    }
}
