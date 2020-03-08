<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRawLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // TODO Lior - what exact data we save in raw_log and check optimization of "request" and "response" fields.
        Schema::create('raw_log', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type_id')->nullable()->index();
            $table->integer('user_id')->nullable();
            $table->text('request');
            $table->text('response');
            $table->text('extra');
            $table->timestamps();
        });
        // TODO Max - there was $table->dropIndex('raw_log_type_id_index');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('raw_log');
    }
}
