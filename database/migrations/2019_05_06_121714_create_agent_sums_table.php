<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentSumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_sums', function (Blueprint $table) {
            $table->increments('id');
            // TODO Lior - why agents sum is related to user? do we have agent role here?
            $table->integer('user_id');
            $table->decimal('total_sum', 14, 5);
            $table->decimal('agent_percent');
            // TODO Lior - What parent_percent is used for and why it is "parent"?
            $table->decimal('parent_percent')->nullable();
            // TODO Lior - What parent_profit is used for and why it is "parent"?
            $table->decimal('parent_profit')->nullable();
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
        Schema::drop('agent_sums');
    }
}
