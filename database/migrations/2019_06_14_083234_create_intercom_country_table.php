<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntercomCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // TODO Lior - Why do we have 2 counties tables? work with one countries table
        Schema::create('intercom_country', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('code', 2);
            $table->string('name');
            $table->bigInteger('intercom_id')->default(1);

            $table->unique('code');
        });

        $seeder = new DatabaseSeeder();

        $seeder->call(
            IntercomCountrySeeder::class
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intercom_country');
    }
}
