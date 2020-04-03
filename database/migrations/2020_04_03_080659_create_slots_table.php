<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 150)->nullable();
            $table->string('display_name', 150)->nullable();
            $table->string('path', 150)->nullable();
            $table->string('swf', 150)->nullable();
            $table->string('image', 150)->nullable();
            $table->integer('room_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('type_id')->nullable();
            $table->tinyInteger('is_mobile')->nullable();
            $table->tinyInteger('is_bonus')->nullable()->default(0);
            $table->tinyInteger('is_working')->nullable()->default(1);
            $table->integer('raiting')->nullable()->default(0);
            $table->string('demo_url', 500)->nullable()->default('');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slots');
    }
}
