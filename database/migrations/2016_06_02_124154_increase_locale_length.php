<?php

use Illuminate\Database\Migrations\Migration;

class IncreaseLocaleLength extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // TODO Lior - why do we have 2 translations edit statements? move to original migration file.
        Schema::table('translator_languages', function ($table) {
            $table->string('locale', 10)->change();
        });
        Schema::table('translator_translations', function ($table) {
            $table->string('locale', 10)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}
