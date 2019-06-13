<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnFreeRoundToGamelist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games_list', function ($table) {
            $table->boolean('free_round')->defalut(0)->after('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games_list', function ($table) {
            $table->dropColumn('free_round');
        });
    }
}
