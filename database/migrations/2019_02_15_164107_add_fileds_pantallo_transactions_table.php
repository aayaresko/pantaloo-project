<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFiledsPantalloTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games_pantallo_transactions', function (Blueprint $table) {
            $table->integer('games_session_id')->nullable()->unsigned()->after('transaction_id');
            $table->foreign('games_session_id')->references('id')->on('games_pantallo_session_game');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games_pantallo_transactions', function (Blueprint $table) {
            $table->dropColumn('games_session_id');
        });
    }
}
