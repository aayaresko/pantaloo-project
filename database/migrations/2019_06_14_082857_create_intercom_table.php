<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntercomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intercom', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email');
            $table->string('appId');
            $table->string('key');
            $table->string('token');

            $table->unique('email');
        });

        $seeder = new DatabaseSeeder();

        $seeder->call(
            IntercomSeeder::class
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intercom');
    }
}
