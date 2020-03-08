<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesPantalloTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games_pantallo_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('game_id')->nullable()->unsigned();
            $table->foreign('game_id')->references('id')->on('games_list');
            $table->string('action_id')->index();
            $table->string('system_id')->index();
            $table->integer('transaction_id')->unsigned()->index();
            $table->foreign('transaction_id')->references('id')
                ->on('transactions')->onDelete('cascade');

            $table->integer('games_session_id')->nullable()->unsigned();
            $table->foreign('games_session_id')->references('id')->on('games_pantallo_session_game');

            // TODO Lior - check decimal() length and make sure we return "float" as number.
            $table->decimal(' ', 14, 5);
            $table->decimal('balance_before', 14, 5)->nullable();
            $table->decimal('balance_after', 14, 5);

            // TODO Lior - what it means "real" action, what is not real?
            $table->tinyInteger('real_action_id');


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
        Schema::drop('games_pantallo_transactions');
    }
}
