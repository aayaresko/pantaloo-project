<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonusLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonus_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bonus_id')->nullable()->unsigned()->index();
            $table->foreign('bonus_id')->references('id')->on('user_bonuses')->onDelete('cascade');

            // TODO Lior - check what is operation_id in bonus_logs table
            $table->tinyInteger('operation_id')->index();
            $table->text('status');
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
        Schema::drop('bonus_logs');
    }
}
