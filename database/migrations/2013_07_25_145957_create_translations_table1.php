<?php

use Illuminate\Database\Migrations\Migration;

class CreateTranslationsTable1 extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translations', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->string('eng', 500);
            $table->string('rus', 500);
            $table->tinyInteger('status')->default(0);
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
        Schema::drop('translator_translations');
    }

}
