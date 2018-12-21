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
            $table->decimal('amount', 14, 5);
            $table->decimal('balance_before', 14, 5)->nullable();
            $table->decimal('balance_after', 14, 5);
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
