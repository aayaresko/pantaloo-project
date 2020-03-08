<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bonuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bonus_id')->nullable()->unsigned()->index();
            $table->foreign('bonus_id')->references('id')->on('bonuses');

            $table->integer('user_id')->nullable()->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');

            // TODO Lior - check how we activate the bonus, SECURITY
            // TODO Lior - check we have limit of bonus logic and how we verify it?
            $table->tinyInteger('activated')->index();
            $table->text('data');
            $table->ipAddress('ip_address')->index();


            $table->timestamp('expires_at')->nullable();
            $table->decimal('total_amount', 14, 5)->default(0);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_bonuses');
    }
}
