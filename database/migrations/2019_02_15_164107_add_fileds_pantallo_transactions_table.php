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
            // TODO Lior - what it means "real" action, what is not real?
            $table->tinyInteger('real_action_id')->after('balance_after');
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
            $table->dropColumn(['games_session_id', 'real_action_id']);
        });
    }
}
