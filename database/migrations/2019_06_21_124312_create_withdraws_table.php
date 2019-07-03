<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraws', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            //$table->integer('type_id')->unsigned()->index(); type withdraw - no actually
            $table->decimal('value', 14, 5);
            $table->text('extra')->nullable();
            //$table->integer('status')->unsigned()->default(0); status notifications
            $table->integer('status_withdraw')->default(0);
            $table->string('to_address');
            $table->string('ext_id')->nullable();
            $table->integer('confirmations')->nullable();
            $table->integer('transaction_id')->unsigned()->nullable()->index();
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
        Schema::dropIfExists('withdraws');
    }
}