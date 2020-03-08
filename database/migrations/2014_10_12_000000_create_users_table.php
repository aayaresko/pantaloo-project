<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class  CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // TODO Lior - MUST to add indexes to users table (name column and check additional columns).
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');

            $table->timestamp('last_activity')->nullable();
            $table->decimal('balance', 14, 5)->nullable();
            $table->decimal('bonus_balance', 14, 5)->default('0.00000');
            $table->integer('free_spins')->default(0);
            $table->tinyInteger('bonus_available')->default(1);
            $table->integer('bonus_id')->nullable();
            $table->integer('agent_id')->nullable()->unique();
            $table->integer('tracker_id')->nullable();
            $table->tinyInteger('commission')->default(0);
            $table->tinyInteger('role')->default(0);
            $table->string('country', 50)->nullable();
            $table->string('ip', 50)->nullable();
            $table->string('bitcoin_address', 50)->unique()->nullable();
            $table->integer('currency_id')->default(1);

            $table->rememberToken();
            $table->timestamps();
        });


        // TODO Max - there was $table->index('bitcoin_address') in migrations, and this column does not exists
        // TODO Max - there was $table->index('agent_id'); in migrations, and this column does not exists
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
