<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');

            $table->string('comment', 150)->nullable();
            $table->decimal('sum', 14, 5)->default('0.00000');
            $table->decimal('bonus_sum', 14, 5)->default('0.00000');
            $table->integer('free_spin')->default(0);
            $table->string('ext_id', 150)->nullable();
            $table->integer('confirmations')->nullable();
            $table->integer('user_id')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->integer('token_id')->nullable();
            $table->integer('round_id')->nullable();
            $table->tinyInteger('withdraw_status')->default(0);
            $table->string('address', 150)->default(0);
            $table->tinyInteger('agent_commission')->default(0);
            $table->integer('agent_id')->default(0);
            $table->tinyInteger('notification')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('transactions');
    }
}
